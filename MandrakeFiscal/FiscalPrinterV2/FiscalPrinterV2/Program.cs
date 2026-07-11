using System;
using FiscalPrinterV2.Services;
using FiscalPrinterV2.Helpers;
using FiscalPrinterV2.Models;
using FiscalPrinterV2.Validators;

namespace FiscalPrinterV2
{
    class Program
    {
        static void Main(string[] args)
        {
            if (args.Length == 1 && System.IO.File.Exists(args[0]))
            {
                var data = IniReader.Read(args[0]);

                FiscalDocumentService docService = new FiscalDocumentService();
                FiscalDocument doc = docService.FromDictionary(data);

                Logger.Info("Archivo DAT recibido: " + args[0]);

                FiscalDocumentValidator validator = new FiscalDocumentValidator();
                string validationError = validator.Validate(doc);

                if (validationError != "")
                {
                    Logger.Error(validationError);
                    Console.WriteLine("{\"success\":false,\"message\":\"" + validationError + "\"}");
                    return;
                }

                FiscalPrinterService fiscalPrinter = new FiscalPrinterService();

                FiscalResponse result;

                if (doc.Tipo == "CHK")
                    result = fiscalPrinter.Check(doc);
                else
                    result = fiscalPrinter.Print(doc);

                Console.WriteLine(result.ToJson());
                return;
            }

            string tipoDoc = args.Length > 0 ? args[0].Trim().ToUpper() : "TEST";
            string puerto = args.Length > 1 ? args[1].Trim().ToUpper() : "COM3";

            if (tipoDoc == "INFO")
            {
                FiscalDocument infoDoc = new FiscalDocument();
                infoDoc.Tipo = "CHK";
                infoDoc.Id = 0;
                infoDoc.Com = puerto;

                FiscalPrinterService fiscal = new FiscalPrinterService();
                FiscalResponse info = fiscal.Check(infoDoc);

                Console.WriteLine(info.ToText());
                return;
            }

            if (tipoDoc == "INFOJSON")
            {
                FiscalPrinterService fiscal = new FiscalPrinterService();

                if (args.Length > 1)
                {
                    FiscalDocument infoDoc = new FiscalDocument();
                    infoDoc.Tipo = "CHK";
                    infoDoc.Id = 0;
                    infoDoc.Com = puerto;

                    FiscalResponse info = fiscal.Check(infoDoc);

                    Console.WriteLine(info.ToJson());
                    return;
                }

                for (int i = 1; i <= 20; i++)
                {
                    string com = "COM" + i;

                    FiscalDocument infoDoc = new FiscalDocument();
                    infoDoc.Tipo = "CHK";
                    infoDoc.Id = 0;
                    infoDoc.Com = com;

                    FiscalResponse info = fiscal.Check(infoDoc);

                    if (info.Success)
                    {
                        Console.WriteLine(info.ToJson());
                        return;
                    }
                }

                FiscalResponse error = new FiscalResponse();
                error.Success = false;
                error.Message = "No se pudo detectar automáticamente la impresora fiscal en COM1-COM20.";
                error.Tipo = "CHK";

                Console.WriteLine(error.ToJson());
                return;
            }

            if (tipoDoc == "S2")
            {
                PrinterService p = new PrinterService();

                if (!p.Connect(puerto))
                {
                    Console.WriteLine("No conecta");
                    return;
                }

                Console.WriteLine(p.GetS2InfoText());

                p.Close();
                return;
            }

            if (tipoDoc == "X" || tipoDoc == "RX")
            {
                PrinterService printerX = new PrinterService();

                if (!printerX.Connect(puerto))
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"No se pudo conectar a la impresora\"}");
                    return;
                }

                bool ok = printerX.PrintXReport();
                printerX.Close();

                Console.WriteLine("{\"success\":" + (ok ? "true" : "false") + ",\"message\":\"Reporte X\"}");
                return;
            }

            if (tipoDoc == "Z" || tipoDoc == "RZ")
            {
                PrinterService printerZ = new PrinterService();

                if (!printerZ.Connect(puerto))
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"No se pudo conectar a la impresora\"}");
                    return;
                }

                bool ok = printerZ.PrintZReport();
                printerZ.Close();

                Console.WriteLine("{\"success\":" + (ok ? "true" : "false") + ",\"message\":\"Reporte Z\"}");
                return;
            }

            if (tipoDoc == "CLOSE")
            {
                PrinterService p = new PrinterService();

                if (!p.Connect(puerto))
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"No conecta\"}");
                    return;
                }

                bool ok1 = p.SendCommand("101"); // pago directo efectivo nacional
                bool ok2 = p.SendCommand("199"); // cierre obligatorio IGTF/flag 50

                p.Close();

                Console.WriteLine("{\"success\":" + ((ok1 && ok2) ? "true" : "false") +
                    ",\"message\":\"Intento de cierre enviado\"}");

                return;
            }

            if (tipoDoc == "RAW")
            {
                if (args.Length < 3)
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"Debe indicar comando RAW\"}");
                    return;
                }

                PrinterService p = new PrinterService();

                if (!p.Connect(puerto))
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"No conecta\"}");
                    return;
                }

                bool ok = p.SendCommand(args[2]);

                p.Close();

                Console.WriteLine("{\"success\":" + (ok ? "true" : "false") +
                    ",\"message\":\"Comando enviado\",\"cmd\":\"" + args[2] + "\"}");

                return;
            }

            if (tipoDoc == "VOID")
            {
                PrinterService p = new PrinterService();

                if (!p.Connect(puerto))
                {
                    Console.WriteLine("{\"success\":false,\"message\":\"No conecta\"}");
                    return;
                }

                bool ok = p.SendCommand("7");

                p.Close();

                Console.WriteLine("{\"success\":" + (ok ? "true" : "false") +
                    ",\"message\":\"Intento de anulación enviado\"}");

                return;
            }


            PrinterService printer = new PrinterService();

            if (!printer.Connect(puerto))
            {
                Console.WriteLine("{\"success\":false,\"message\":\"No se pudo conectar a la impresora\",\"port\":\"" + puerto + "\"}");
                return;
            }

            switch (tipoDoc)
            {
                case "TEST":
                    Console.WriteLine("{\"success\":true,\"message\":\"Comunicación exitosa\",\"port\":\"" + puerto +
                        "\",\"status\":\"" + printer.GetStatus() + "\"}");
                    break;

                case "PR":
                    if (printer.PrintTest())
                        Console.WriteLine("{\"success\":true,\"message\":\"Prueba no fiscal impresa\"}");
                    else
                        Console.WriteLine("{\"success\":false,\"message\":\"No se pudo imprimir\"}");
                    break;
                default:
                    Console.WriteLine("{\"success\":false,\"message\":\"Tipo de documento inválido\"}");
                    break;
            }

            printer.Close();
        }
    }
}