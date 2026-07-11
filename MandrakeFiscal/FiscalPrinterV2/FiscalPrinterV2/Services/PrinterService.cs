using System;
using TfhkaNet.IF.VE;
using TfhkaNet.IF;
using FiscalPrinterV2.Models;
using FiscalPrinterV2.Helpers;

namespace FiscalPrinterV2.Services
{
    public class PrinterService
    {
        private readonly Tfhka _printer;

        public PrinterService()
        {
            _printer = new Tfhka();
        }

        public bool Connect(string port)
        {
            try
            {
                Logger.Info("Abriendo puerto: " + port);

                if (!_printer.OpenFpCtrl(port))
                    return false;

                return _printer.CheckFPrinter();
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public bool SendCommand(string command)
        {
            try
            {
                Logger.Info("CMD => " + command);

                bool ok = _printer.SendCmd(command);

                Logger.Info("CMD RESULT => " + (ok ? "OK" : "ERROR"));

                return ok;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public string FormatAmount(decimal value, int width)
        {
            int cents = (int)Math.Round(value * 100, 0);
            return cents.ToString().PadLeft(width, '0');
        }

        public string FormatQuantity(decimal value)
        {
            int qty = (int)Math.Round(value * 1000, 0);
            return qty.ToString().PadLeft(8, '0');
        }

        public string GetStatus()
        {
            try
            {
                PrinterStatus status = _printer.GetPrinterStatus();
                return _printer.Estado;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return ex.Message;
            }
        }

        public void Close()
        {
            try
            {
                _printer.CloseFpCtrl();
                Logger.Info("Puerto cerrado.");
            }
            catch
            {
            }
        }

        public bool PrintTest()
        {
            try
            {
                if (!SendCommand("80$TEST COMUNICACION"))
                    return false;

                if (!SendCommand("81"))
                    return false;

                return true;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public bool PrintDocumentTest(FiscalDocument doc)
        {
            try
            {
                if (!SendCommand("80$DOCUMENTO TEST"))
                    return false;

                SendCommand("80!ID: " + doc.Id);
                SendCommand("80!TIPO: " + doc.Tipo);
                SendCommand("80!CLIENTE: " + doc.Cliente);
                SendCommand("80!RIF: " + doc.Rif);
                SendCommand("80!ITEMS: " + doc.Items.Count);

                if (doc.Tipo == "NC" || doc.Tipo == "ND")
                {
                    SendCommand("80!FACTURA_AFECTADA: " + doc.FacturaAfectada);
                    SendCommand("80!FECHA_AFECTADA: " + doc.FechaAfectada);
                    SendCommand("80!SERIAL_AFECTADA: " + doc.SerialAfectada);
                }

                foreach (var item in doc.Items)
                {
                    SendCommand("80!" + item.Descripcion);
                }

                if (!SendCommand("81"))
                    return false;

                return true;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public FiscalResponse GetFiscalResponse(FiscalDocument doc, bool success, string message)
        {
            FiscalResponse response = new FiscalResponse();

            response.Success = success;
            response.Message = message;
            response.Id = doc.Id;
            response.Tipo = doc.Tipo;
            response.Puerto = doc.Com;

            try
            {
                PrinterStatus status = _printer.GetPrinterStatus();

                response.EstadoImpresora = status.PrinterStatusDescription;
                response.ErrorImpresora = status.PrinterErrorDescription;
            }
            catch
            {
                response.EstadoImpresora = _printer.Estado;
            }

            try
            {
                S1PrinterData s1 = _printer.GetS1PrinterData();

                response.NumeroFactura = s1.LastInvoiceNumber.ToString();
                response.NumeroNotaCredito = s1.LastCreditNoteNumber.ToString();
                response.NumeroNotaDebito = s1.LastDebitNoteNumber.ToString();
                response.SerialFiscal = s1.RegisteredMachineNumber;
                response.RifImpresora = s1.RIF;
                response.FechaHoraImpresora = s1.CurrentPrinterDateTime.ToString("yyyy-MM-dd HH:mm:ss");
            }
            catch
            {
            }

            return response;
        }

        public bool PrintXReport()
        {
            try
            {
                _printer.PrintXReport();
                return true;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public bool PrintZReport()
        {
            try
            {
                _printer.PrintZReport();
                return true;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public string FormatPaymentAmount(decimal value)
        {
            int cents = (int)Math.Round(value * 100, 0);
            return cents.ToString().PadLeft(12, '0');
        }

        public string GetPaymentCode(FiscalPayment pago)
        {
            string codigo = (pago.Codigo ?? "").ToUpper();
            string moneda = (pago.Moneda ?? "").ToUpper();

            // Moneda extranjera: 20 al 24
            if (moneda == "USD" || moneda == "EURO")
            {
                switch (codigo)
                {
                    case "EF": return "20"; // Divisa 1
                    case "TD": return "21"; // Divisa 2
                    case "TC": return "22"; // Divisa 3
                    case "TR": return "23"; // Divisa 4
                    case "PM": return "24"; // Divisa 5
                    default: return "20";
                }
            }

            // Moneda nacional: 01 al 19
            switch (codigo)
            {
                case "EF": return "01"; // Efectivo 1
                case "CH": return "07"; // Cheque 1
                case "TD": return "13"; // Tarjeta 1
                case "TC": return "14"; // Tarjeta 2
                case "TR": return "15"; // Tarjeta 3 / configurable
                case "PM": return "16"; // Tarjeta 4 / configurable como Pago Móvil
                default: return "01";
            }
        }

        public decimal ConvertToFiscalAmount(decimal monto, string moneda, decimal tasa)
        {
            if (string.IsNullOrWhiteSpace(moneda))
                return monto;

            moneda = moneda.Trim().ToUpper();

            switch (moneda)
            {
                case "USD":
                case "EURO":
                    return Math.Round(monto * tasa, 2);

                default:
                    return monto;
            }
        }

        // Determina si el documento aplica IGTF basándose en los medios de
        // pago realmente recibidos en el .dat (moneda distinta a Bs), en vez
        // de depender de un flag precalculado aparte. Esto es lo que decide
        // si el comando 199 de cierre es obligatorio (flag 50 en 01).
        public bool UsaPagoEnDivisa(FiscalDocument doc)
        {
            if (doc.Pagos == null) return false;

            foreach (var pago in doc.Pagos)
            {
                string moneda = (pago.Moneda ?? "").Trim().ToUpper();

                if (moneda != "" && moneda != "BS" && moneda != "BS." && moneda != "BOLIVARES")
                    return true;
            }

            return false;
        }

        public bool OpenOnly(string port)
        {
            try
            {
                Logger.Info("Abriendo puerto sin CheckFPrinter: " + port);
                return _printer.OpenFpCtrl(port);
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return false;
            }
        }

        public string GetS2InfoText()
        {
            try
            {
                S2PrinterData s2 = _printer.GetS2PrinterData();

                return
                    "AmountPayable=" + s2.AmountPayable +
                    ";Condition=" + s2.Condition +
                    ";NumberPaymentsMade=" + s2.NumberPaymentsMade +
                    ";QuantityArticles=" + s2.QuantityArticles +
                    ";SubTotalBases=" + s2.SubTotalBases +
                    ";SubTotalTax=" + s2.SubTotalTax +
                    ";TypeDocument=" + s2.TypeDocument;
            }
            catch (Exception ex)
            {
                Logger.Error(ex);
                return "ERROR S2: " + ex.Message;
            }
        }
    }
}