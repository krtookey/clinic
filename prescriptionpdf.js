function htmlToPdf() {
    var doc = new jsPDF();
     doc.fromHTML(document.getElementById("pdf_text"), 15, 15, {'width': 170}, function(){doc.save("Prescription.pdf");});
   }