using System;
using System.Collections.Generic;
using System.Globalization;
using FiscalPrinterV2.Models;

namespace FiscalPrinterV2.Services
{
    public class FiscalDocumentService
    {
        public FiscalDocument FromDictionary(Dictionary<string, string> data)
        {
            FiscalDocument doc = new FiscalDocument();

            doc.Com = Get(data, "COM");
            doc.Tipo = Get(data, "TIPO").ToUpper();
            doc.Id = ToInt(Get(data, "ID"));
            doc.Usuario = Get(data, "USUARIO");

            doc.Rif = Get(data, "RIF");
            doc.Cliente = Get(data, "CLIENTE");
            doc.Direccion = Get(data, "DIRECCION");
            doc.Telefono = Get(data, "TELEFONO");

            doc.Moneda = Get(data, "MONEDA");
            doc.Tasa = ToDecimal(Get(data, "TASA"));

            doc.IgtfAplica = Get(data, "IGTF_APLICA").ToUpper();
            doc.IgtfAlicuota = ToDecimal(Get(data, "IGTF_ALICUOTA"));

            doc.FacturaAfectada = Get(data, "FACTURA_AFECTADA");
            doc.FechaAfectada = Get(data, "FECHA_AFECTADA");
            doc.SerialAfectada = Get(data, "SERIAL_AFECTADA");

            doc.ModoPrueba = Get(data, "MODO_PRUEBA").ToUpper() == "S";

            CargarItems(doc, data);
            CargarPagos(doc, data);

            return doc;
        }

        private void CargarItems(FiscalDocument doc, Dictionary<string, string> data)
        {
            int items = ToInt(Get(data, "ITEMS"));

            for (int i = 1; i <= items; i++)
            {
                string line = Get(data, "ITEM" + i);

                if (line == "")
                    continue;

                string[] parts = line.Split('|');

                if (parts.Length < 4)
                    continue;

                FiscalItem item = new FiscalItem();
                item.Descripcion = parts[0].Trim();
                item.Cantidad = ToDecimal(parts[1]);
                item.Precio = ToDecimal(parts[2]);
                item.Alicuota = ToDecimal(parts[3]);

                doc.Items.Add(item);
            }
        }

        private void CargarPagos(FiscalDocument doc, Dictionary<string, string> data)
        {
            int pagos = ToInt(Get(data, "PAGOS"));

            for (int i = 1; i <= pagos; i++)
            {
                string line = Get(data, "PAGO" + i);

                if (line == "")
                    continue;

                string[] parts = line.Split('|');

                if (parts.Length < 4)
                    continue;

                FiscalPayment pago = new FiscalPayment();
                pago.Codigo = parts[0].Trim().ToUpper();
                pago.Descripcion = parts[1].Trim();
                pago.Monto = ToDecimal(parts[2]);
                pago.Moneda = parts[3].Trim().ToUpper();

                doc.Pagos.Add(pago);
            }
        }

        private string Get(Dictionary<string, string> data, string key)
        {
            return data.ContainsKey(key) ? data[key] : "";
        }

        private int ToInt(string value)
        {
            int result;
            int.TryParse(value, out result);
            return result;
        }

        private decimal ToDecimal(string value)
        {
            decimal result;

            decimal.TryParse(
                value.Replace(",", "."),
                NumberStyles.Any,
                CultureInfo.InvariantCulture,
                out result
            );

            return result;
        }
    }
}