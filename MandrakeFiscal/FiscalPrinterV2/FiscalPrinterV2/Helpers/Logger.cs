using System;
using System.IO;

namespace FiscalPrinterV2.Helpers
{
    public static class Logger
    {
        public static void Info(string message)
        {
            Write("INFO", message);
        }

        public static void Error(string message)
        {
            Write("ERROR", message);
        }

        public static void Error(Exception ex)
        {
            Write("ERROR", ex.ToString());
        }

        private static void Write(string level, string message)
        {
            try
            {
                string logDir = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "Logs");

                if (!Directory.Exists(logDir))
                    Directory.CreateDirectory(logDir);

                string file = Path.Combine(logDir, DateTime.Now.ToString("yyyyMMdd") + ".log");

                string line = DateTime.Now.ToString("HH:mm:ss") +
                              " [" + level + "] " +
                              message;

                File.AppendAllText(file, line + Environment.NewLine);
            }
            catch
            {
            }
        }
    }
}