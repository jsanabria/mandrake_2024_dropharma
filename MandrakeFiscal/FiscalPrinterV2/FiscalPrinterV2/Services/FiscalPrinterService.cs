using FiscalPrinterV2.Models;
using FiscalPrinterV2.Documents;

namespace FiscalPrinterV2.Services
{
    public class FiscalPrinterService
    {
        public FiscalResponse Print(FiscalDocument doc)
        {
            PrinterService printer = new PrinterService();
            try
            {
                if (!printer.Connect(doc.Com))
                {
                    return new FiscalResponse
                    {
                        Success = false,
                        Message = "No se pudo conectar a la impresora",
                        Id = doc.Id,
                        Tipo = doc.Tipo,
                        Puerto = doc.Com
                    };
                }
                bool ok = false;
                switch (doc.Tipo)
                {
                    case "FC":
                        FacturaPrinter factura = new FacturaPrinter(printer);
                        ok = doc.ModoPrueba ? factura.PrintTest(doc) : factura.PrintFiscal(doc);
                        break;
                    case "NC":
                        NotaCreditoPrinter notaCredito = new NotaCreditoPrinter(printer);
                        ok = doc.ModoPrueba ? notaCredito.PrintTest(doc) : notaCredito.PrintFiscal(doc);
                        break;
                    case "ND":
                        NotaDebitoPrinter notaDebito = new NotaDebitoPrinter(printer);
                        ok = doc.ModoPrueba ? notaDebito.PrintTest(doc) : notaDebito.PrintFiscal(doc);
                        break;
                    default:
                        return printer.GetFiscalResponse(doc, false, "Tipo de documento no implementado");
                }
                return printer.GetFiscalResponse(
                    doc,
                    ok,
                    ok ? "Documento impreso correctamente" : "Error imprimiendo documento"
                );
            }
            finally
            {
                printer.Close();
            }
        }

        public FiscalResponse Check(FiscalDocument doc)
        {
            PrinterService printer = new PrinterService();
            try
            {
                if (!printer.Connect(doc.Com))
                {
                    return new FiscalResponse
                    {
                        Success = false,
                        Message = "No se pudo conectar a la impresora",
                        Id = doc.Id,
                        Tipo = doc.Tipo,
                        Puerto = doc.Com
                    };
                }
                return printer.GetFiscalResponse(doc, true, "Verificación completada sin imprimir");
            }
            finally
            {
                printer.Close();
            }
        }
    }
}