using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace FiscalPrinterV2.Models
{
    public class ConfigModel
    {
        public string Server { get; set; }
        public string Database { get; set; }
        public string User { get; set; }
        public string Password { get; set; }
        public string ImpresoraFiscal { get; set; }

        public string ConnectionString
        {
            get
            {
                return "Server=" + Server +
                       ";Database=" + Database +
                       ";Uid=" + User +
                       ";Pwd=" + Password +
                       ";SslMode=None" +
                       ";CharSet=utf8mb4;";
            }
        }
    }
}
