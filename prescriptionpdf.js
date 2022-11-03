function htmlToPdf() {
    var doc = new jsPDF();
     doc.fromHTML(document.getElementById("data"), 15, 15, {'width': 170}, function(){doc.save("PDF_Documet.pdf");});
   }