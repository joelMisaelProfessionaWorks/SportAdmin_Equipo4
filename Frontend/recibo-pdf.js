// js/recibo-pdf.js
function generarTicketPDF(transaccion) {
    if (!window.jspdf) {
        console.error("jsPDF no está cargado.");
        alert("Error: Librería de PDF no disponible.");
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: [80, 150] });

    // Encabezado
    doc.setFont("helvetica", "bold");
    doc.setFontSize(14);
    doc.text("CLUB DEPORTIVO", 40, 15, { align: "center" });
    doc.text("LEON SALTILLO", 40, 21, { align: "center" });
    
    doc.setFontSize(10);
    doc.setFont("helvetica", "normal");
    doc.text("---------------------------------------------------------", 40, 28, { align: "center" });
    
    // Título del Ticket
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text(`TICKET DE ${transaccion.tipo_pago || 'PAGO'}`, 40, 36, { align: "center" });
    
    // Metadatos de Transacción
    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.text(`Folio: ${transaccion.folio || 'N/A'}`, 10, 45);
    doc.text(`Fecha: ${transaccion.fecha || new Date().toISOString().split('T')[0]}`, 10, 50);
    doc.text(`Hora: ${transaccion.hora || new Date().toLocaleTimeString()}`, 10, 55);
    doc.text(`Auth: ${transaccion.autorizacion_bancaria || 'N/A'}`, 10, 60);
    
    doc.text("---------------------------------------------------------", 40, 67, { align: "center" });
    
    // Detalles del cobro
    doc.setFont("helvetica", "bold");
    doc.text("CONCEPTO:", 10, 75);
    doc.setFont("helvetica", "normal");
    doc.text(transaccion.concepto || 'Pago General', 10, 80);
    
    doc.setFont("helvetica", "bold");
    doc.text("EQUIPO / JUGADOR:", 10, 88);
    doc.setFont("helvetica", "normal");
    doc.text(transaccion.equipo_nombre || 'N/A', 10, 93);

    doc.setFont("helvetica", "bold");
    doc.text("METODO PAGO:", 10, 101);
    doc.setFont("helvetica", "normal");
    doc.text(transaccion.metodo_pago || 'N/A', 10, 106);
    
    doc.text("---------------------------------------------------------", 40, 114, { align: "center" });
    
    // Total
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    const montoFormat = parseFloat(transaccion.monto || 0).toFixed(2);
    doc.text(`TOTAL: $${montoFormat} MXN`, 40, 124, { align: "center" });
    
    // Pie de página
    doc.setFontSize(8);
    doc.setFont("helvetica", "italic");
    doc.text("¡Gracias por su pago!", 40, 138, { align: "center" });
    doc.text("Este ticket es su comprobante oficial.", 40, 143, { align: "center" });

    // Guardar
    const nombreArchivo = `Ticket_${transaccion.tipo_pago}_${(transaccion.equipo_nombre || '').replace(/\s+/g, '_')}_${transaccion.folio}.pdf`;
    doc.save(nombreArchivo);
}
