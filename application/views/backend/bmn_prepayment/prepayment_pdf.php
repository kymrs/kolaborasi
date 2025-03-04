<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepayment</title>
    <link rel="icon" href="<?= base_url() ?>/assets/backend/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url() ?>assets\backend\plugins/pdfjs/pdf_viewer.min.css">
</head>

<body>
    <!-- PDF.js Toolbar and Viewer -->
    <div id="viewerContainer" class="pdfViewer"></div>


    <script src="<?= base_url() ?>assets\backend\plugins\pdfjs\pdf.min.js"></script>
    <script src="<?= base_url() ?>assets\backend\plugins\pdfjs\pdf.worker.min.js"></script>
    <script src="<?= base_url() ?>assets\backend\plugins\pdfjs\pdf_viewer.min.js"></script>


    <script>
        fetch('<?= base_url() ?>/prepayment_bmn/generate_pdf/150').then(response => response.json())
            .then(base64Pdf => {
                // Decode the base64-encoded PDF string
                const pdfData = atob(base64Pdf);

                // Convert the string to a typed array
                const pdfArray = new Uint8Array(pdfData.length);
                for (let i = 0; i < pdfData.length; i++) {
                    pdfArray[i] = pdfData.charCodeAt(i);
                }

                // Load the PDF
                const loadingTask = pdfjsLib.getDocument({
                    data: pdfArray
                });
                loadingTask.promise.then(function(pdf) {
                    // Fetch the first page
                    // pdf.getPage(1).then(function(page) {



                    //     const scale = 1.5;
                    //     const viewport = page.getViewport({
                    //         scale: scale
                    //     });

                    //     // Prepare canvas using PDF page dimensions
                    //     const canvas = document.getElementById('pdf-canvas');
                    //     const context = canvas.getContext('2d');
                    //     canvas.height = viewport.height;
                    //     canvas.width = viewport.width;

                    //     // Render the PDF page into the canvas context
                    //     const renderContext = {
                    //         canvasContext: context,
                    //         viewport: viewport
                    //     };
                    //     page.render(renderContext);
                    // });

                    // PDF loaded successfully
                    // Now display it in the viewer

                    // Container for the PDF.js viewer
                    const container = document.getElementById('viewerContainer');

                    // Fetch and display each page
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        pdf.getPage(pageNum).then(function(page) {
                            const viewport = page.getViewport({
                                scale: 1.5
                            });

                            // Create a canvas element
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas
                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            page.render(renderContext);

                            // Append the canvas to the viewer container
                            container.appendChild(canvas);
                        });
                    }
                });
            })
            .catch(error => console.error(error));
    </script>

</body>

</html>