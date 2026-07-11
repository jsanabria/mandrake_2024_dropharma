using System;
using System.Collections.Generic;
using System.IO;

namespace FiscalPrinterV2.Helpers
{
    public class IniReader
    {
        public static Dictionary<string, string> Read(string path)
        {
            if (!File.Exists(path))
                throw new FileNotFoundException("No existe el archivo: " + path);

            Dictionary<string, string> data = new Dictionary<string, string>();

            string[] lines = File.ReadAllLines(path);

            foreach (string rawLine in lines)
            {
                string line = rawLine.Trim();

                if (line == "" || line.StartsWith("#") || line.StartsWith(";"))
                    continue;

                string[] parts = line.Split(new char[] { '=' }, 2);

                if (parts.Length != 2)
                    continue;

                data[parts[0].Trim().ToUpper()] = parts[1].Trim();
            }

            return data;
        }
    }
}