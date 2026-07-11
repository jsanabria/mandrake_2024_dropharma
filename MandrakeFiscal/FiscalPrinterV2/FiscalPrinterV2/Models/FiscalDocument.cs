using System.Collections.Generic;

namespace FiscalPrinterV2.Models
{
    public class FiscalDocument
    {
        public string Com { get; set; }
        public string Tipo { get; set; }
        public int Id { get; set; }
        public string Usuario { get; set; }
        public string Rif { get; set; }
        public string Cliente { get; set; }
        public string Direccion { get; set; }
        public string Telefono { get; set; }
        // ===== NUEVOS CAMPOS =====
        public string Moneda { get; set; }
        public decimal Tasa { get; set; }
        public string IgtfAplica { get; set; }
        public decimal IgtfAlicuota { get; set; }
        public string FacturaAfectada { get; set; }
        public string FechaAfectada { get; set; }
        public string SerialAfectada { get; set; }
        // Indica si el documento debe imprimirse como NO fiscal (PrintTest)
        // o como fiscal real (PrintFiscal). Viene de la línea MODO_PRUEBA=S/N
        // del archivo .dat, controlado por el flag $MODO_PRUEBA_FISCAL en PHP.
        public bool ModoPrueba { get; set; }
        // =========================
        public List<FiscalItem> Items { get; set; }
        public List<FiscalPayment> Pagos { get; set; }
        public FiscalDocument()
        {
            Items = new List<FiscalItem>();
            Pagos = new List<FiscalPayment>();
        }
    }
    public class FiscalItem
    {
        public string Descripcion { get; set; }
        public decimal Cantidad { get; set; }
        public decimal Precio { get; set; }
        public decimal Alicuota { get; set; }
    }
    public class FiscalPayment
    {
        public string Codigo { get; set; }
        public string Descripcion { get; set; }
        public decimal Monto { get; set; }
        public string Moneda { get; set; }
    }
}