using FiscalPrinterV2.Models;
using FiscalPrinterV2.Services;

namespace FiscalPrinterV2.Documents
{
    public class FacturaPrinter
    {
        private readonly PrinterService _printer;

        public FacturaPrinter(PrinterService printer)
        {
            _printer = printer;
        }

        public bool PrintTest(FiscalDocument doc)
        {
            return _printer.PrintDocumentTest(doc);
        }

        public bool PrintFiscal(FiscalDocument doc)
        {
            if (!_printer.SendCommand("iR*" + doc.Rif)) return false;
            if (!_printer.SendCommand("iS*" + doc.Cliente)) return false;

            _printer.SendCommand("i01Direccion: " + doc.Direccion);
            _printer.SendCommand("i02Telefono: " + doc.Telefono);
            _printer.SendCommand("i03Usuario: " + doc.Usuario);

            foreach (var item in doc.Items)
            {
                string precio = _printer.FormatAmount(item.Precio, 10);
                string cantidad = _printer.FormatQuantity(item.Cantidad);
                string descripcion = item.Descripcion;

                string comando = precio + cantidad + descripcion;

                if (item.Alicuota > 0)
                {
                    if (!_printer.SendCommand("!" + comando)) return false;
                }
                else
                {
                    if (!_printer.SendCommand(" " + comando)) return false;
                }
            }

            if (!_printer.SendCommand("3")) return false;

            if (!EnviarPagos(doc))
                return false;

            // El comando 199 solo es obligatorio (y válido) cuando el flag 50
            // de la impresora está en 01, es decir, cuando el documento usa
            // IGTF/pago en divisas (medios de pago 20-24). Se determina según
            // los medios de pago realmente recibidos en el .dat, no según un
            // flag precalculado aparte. Si el documento se pagó en moneda
            // nacional, el propio comando de pago ya cierra el documento
            // fiscal (Manual de Protocolos MIA-CINT-01, sección 24, punto 5).
            if (_printer.UsaPagoEnDivisa(doc))
            {
                if (!_printer.SendCommand("199")) return false;
            }

            return true;
        }

        private bool EnviarPagos(FiscalDocument doc)
        {
            if (doc.Pagos == null || doc.Pagos.Count == 0)
                return _printer.SendCommand("101");

            for (int i = 0; i < doc.Pagos.Count; i++)
            {
                FiscalPayment pago = doc.Pagos[i];

                string medio = _printer.GetPaymentCode(pago);

                decimal montoFiscal =
                    _printer.ConvertToFiscalAmount(
                        pago.Monto,
                        pago.Moneda,
                        doc.Tasa);

                bool ultimo = (i == doc.Pagos.Count - 1);

                if (!ultimo)
                {
                    string monto = _printer.FormatPaymentAmount(montoFiscal);

                    string cmd = "2" + medio + monto;

                    if (!_printer.SendCommand(cmd))
                        return false;
                }
                else
                {
                    string cmd = "1" + medio;

                    if (!_printer.SendCommand(cmd))
                        return false;
                }
            }

            return true;
        }
    }
}