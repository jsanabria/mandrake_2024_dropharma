using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace FiscalPrinterV2.Models
{
    public class FiscalResponse
    {
        public bool Success { get; set; }
        public string Message { get; set; }

        public int Id { get; set; }
        public string Tipo { get; set; }
        public string Puerto { get; set; }

        public string NumeroFactura { get; set; }
        public string NumeroControl { get; set; }
        public string NumeroNotaCredito { get; set; }
        public string NumeroNotaDebito { get; set; }

        public string SerialFiscal { get; set; }
        public string RifImpresora { get; set; }
        public string FechaHoraImpresora { get; set; }

        public string EstadoImpresora { get; set; }
        public string ErrorImpresora { get; set; }

        public string ToJson()
        {
            return "{"
                + "\"success\":" + (Success ? "true" : "false") + ","
                + "\"message\":\"" + Escape(Message) + "\","
                + "\"id\":" + Id + ","
                + "\"tipo\":\"" + Escape(Tipo) + "\","
                + "\"puerto\":\"" + Escape(Puerto) + "\","
                + "\"numeroFactura\":\"" + Escape(NumeroFactura) + "\","
                + "\"numeroControl\":\"" + Escape(NumeroControl) + "\","
                + "\"numeroNotaCredito\":\"" + Escape(NumeroNotaCredito) + "\","
                + "\"numeroNotaDebito\":\"" + Escape(NumeroNotaDebito) + "\","
                + "\"serialFiscal\":\"" + Escape(SerialFiscal) + "\","
                + "\"rifImpresora\":\"" + Escape(RifImpresora) + "\","
                + "\"fechaHoraImpresora\":\"" + Escape(FechaHoraImpresora) + "\","
                + "\"estadoImpresora\":\"" + Escape(EstadoImpresora) + "\","
                + "\"errorImpresora\":\"" + Escape(ErrorImpresora) + "\""
                + "}";
        }

        public string ToText()
        {
            return
                "=========================================\r\n" +
                "      IMPRESORA FISCAL HKA\r\n" +
                "=========================================\r\n\r\n" +

                "Puerto............. " + Puerto + "\r\n" +
                "Serial............ " + SerialFiscal + "\r\n" +
                "RIF............... " + RifImpresora + "\r\n\r\n" +

                "Última Factura.... " + NumeroFactura + "\r\n" +
                "Última N/C........ " + NumeroNotaCredito + "\r\n" +
                "Última N/D........ " + NumeroNotaDebito + "\r\n\r\n" +

                "Estado............ " + EstadoImpresora + "\r\n" +
                "Error............. " + ErrorImpresora + "\r\n\r\n" +

                "Fecha/Hora........ " + FechaHoraImpresora + "\r\n\r\n" +

                "Mensaje........... " + Message + "\r\n" +

                "=========================================";
        }

        private string Escape(string value)
        {
            if (value == null) return "";
            return value.Replace("\\", "\\\\").Replace("\"", "\\\"");
        }
    }
}