using FiscalPrinterV2.Models;

namespace FiscalPrinterV2.Validators
{
    public class FiscalDocumentValidator
    {
        public string Validate(FiscalDocument doc)
        {
            if (doc == null)
                return "Documento fiscal vacío.";

            if (string.IsNullOrWhiteSpace(doc.Com))
                return "Debe indicar el puerto COM.";

            if (string.IsNullOrWhiteSpace(doc.Tipo))
                return "Debe indicar el tipo de documento.";

            if (doc.Tipo != "FC" && doc.Tipo != "NC" && doc.Tipo != "ND" && doc.Tipo != "CHK")
                return "Tipo de documento no válido.";

            if (doc.Id <= 0)
                return "Debe indicar el ID del documento Mandrake.";

            if (string.IsNullOrWhiteSpace(doc.Rif))
                return "Debe indicar el RIF del cliente.";

            if (string.IsNullOrWhiteSpace(doc.Cliente))
                return "Debe indicar el nombre del cliente.";

            if (doc.Items == null || doc.Items.Count == 0)
                return "El documento no tiene artículos.";

            foreach (var item in doc.Items)
            {
                if (string.IsNullOrWhiteSpace(item.Descripcion))
                    return "Existe un artículo sin descripción.";

                if (item.Cantidad <= 0)
                    return "Existe un artículo con cantidad inválida.";

                if (item.Precio < 0)
                    return "Existe un artículo con precio inválido.";

                if (item.Alicuota < 0)
                    return "Existe un artículo con alícuota inválida.";
            }

            if (doc.Pagos == null || doc.Pagos.Count == 0)
                return "Debe indicar al menos una forma de pago.";

            foreach (var pago in doc.Pagos)
            {
                if (string.IsNullOrWhiteSpace(pago.Codigo))
                    return "Existe un pago sin código.";

                if (string.IsNullOrWhiteSpace(pago.Descripcion))
                    return "Existe un pago sin descripción.";

                if (pago.Monto <= 0)
                    return "Existe un pago con monto inválido.";

                if (string.IsNullOrWhiteSpace(pago.Moneda))
                    return "Existe un pago sin moneda.";
            }

            return "";
        }
    }
}