using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace FiscalPrinterV2.Models
{
    public class OperationResult
    {
        public bool Success { get; set; }
        public string Message { get; set; }
        public string Port { get; set; }
        public string Type { get; set; }
        public int Id { get; set; }

        public string ToJson()
        {
            return "{"
                + "\"success\":" + (Success ? "true" : "false") + ","
                + "\"message\":\"" + Escape(Message) + "\","
                + "\"port\":\"" + Escape(Port) + "\","
                + "\"type\":\"" + Escape(Type) + "\","
                + "\"id\":" + Id
                + "}";
        }

        private string Escape(string value)
        {
            if (value == null) return "";
            return value.Replace("\\", "\\\\").Replace("\"", "\\\"");
        }
    }
}