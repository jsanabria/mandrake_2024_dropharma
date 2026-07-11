using System;
using System.Collections.Generic;
using System.IO;
using FiscalPrinterV2.Models;

namespace FiscalPrinterV2.Services
{
    public class ConfigService
    {
        public static ConfigModel Load()
        {
            string path = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "db.ini");

            if (!File.Exists(path))
                throw new FileNotFoundException("No se encontró el archivo db.ini en: " + path);

            string content = File.ReadAllText(path);

            Dictionary<string, string> values = new Dictionary<string, string>();

            string[] parts = content.Split(';');

            foreach (string part in parts)
            {
                if (string.IsNullOrWhiteSpace(part))
                    continue;

                string[] item = part.Split(new char[] { '=' }, 2);

                if (item.Length == 2)
                {
                    string key = item[0].Trim().ToUpper();
                    string value = item[1].Trim();

                    values[key] = value;
                }
            }

            return new ConfigModel
            {
                Server = GetValue(values, "SERVER"),
                Database = GetValue(values, "DB"),
                User = GetValue(values, "USER"),
                Password = GetValue(values, "PASS"),
                ImpresoraFiscal = GetValue(values, "IMPRESORA_FISCAL")
            };
        }

        private static string GetValue(Dictionary<string, string> values, string key)
        {
            if (values.ContainsKey(key))
                return values[key];

            return "";
        }
    }
}