using FiscalPrinterV2.Models;
using FiscalPrinterV2.Services;

namespace FiscalPrinterV2.Documents
{
    public class NotaCreditoPrinter
    {
        private readonly PrinterService _printer;

        public NotaCreditoPrinter(PrinterService printer)
        {
            _printer = printer;
        }

        public bool PrintTest(FiscalDocument doc)
        {
            return _printer.PrintDocumentTest(doc);
        }

        public bool PrintFiscal(FiscalDocument doc)
        {
            if (!_printer.SendCommand("iF*" + doc.FacturaAfectada)) return false;
            if (!_printer.SendCommand("iD*" + doc.FechaAfectada)) return false;
            if (!_printer.SendCommand("iI*" + doc.SerialAfectada)) return false;

            if (!_printer.SendCommand("iR*" + doc.Rif)) return false;
            if (!_printer.SendCommand("iS*" + doc.Cliente)) return false;

            _printer.SendCommand("i01Direccion: " + doc.Direccion);
            _printer.SendCommand("i02Telefono: " + doc.Telefono);
            _printer.SendCommand("i03Usuario: " + doc.Usuario);

            foreach (var item in doc.Items)
            {
                string precio = _printer.FormatAmount(item.Precio, 10);
                string cantidad = _printer.FormatQuantity(item.Cantidad);
                string comando = precio + cantidad + item.Descripcion;

                if (item.Alicuota > 0)
                {
                    if (!_printer.SendCommand("d1" + comando)) return false;
                }
                else
                {
                    if (!_printer.SendCommand("d0" + comando)) return false;
                }
            }

            if (!_printer.SendCommand("3")) return false;

            if (!EnviarPagos(doc))
                return false;

            // Ver nota en FacturaPrinter.PrintFiscal: 199 solo aplica si algún
            // medio de pago viene en divisa (flag 50 en 01).
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
                bool ultimoPago = (i == doc.Pagos.Count - 1);

                if (ultimoPago)
                {
                    // Pago directo: completa el saldo pendiente
                    if (!_printer.SendCommand("1" + medio))
                        return false;
                }
                else
                {
                    // Pago parcial
                    string monto = _printer.FormatPaymentAmount(pago.Monto);

                    if (!_printer.SendCommand("2" + medio + monto))
                        return false;
                }
            }

            return true;
        }
    }
}