<?php include __DIR__ . '/layout/header.php'; ?>
<link rel="stylesheet" href="css/pixelart.css">
<br></br>
<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow" style="width: auto; max-width: 100%;">
        <div class="card-body">
            <h1 class="mb-3 text-center pixelart-title">Pixel Art Paintbrush</h1>
            <div class="d-flex flex-row">
                <div id="pixelCanvasContainer" style="overflow:auto; border:1px solid #ccc; background:#fff; display:inline-block;"></div>
                <div class="d-flex flex-column align-items-start ms-4" style="min-width:180px;">
                    <label for="canvasSize" class="form-label mt-2">Tamaño del lienzo:</label>
                    <select id="canvasSize" class="form-select form-select-sm mb-2" style="width:auto;">
                        <option value="16">16x16</option>
                        <option value="32" selected>32x32</option>
                        <option value="64">64x64</option>
                        <option value="128">128x128</option>
                    </select>
                    
                    <label for="layerSelect" class="form-label mt-2">Capa:</label>
                    <select id="layerSelect" class="form-select form-select-sm mb-2" style="width:auto;"></select>
                    <button id="addLayer" class="btn btn-success btn-sm w-100 mb-2">Agregar Capa</button>
                    <button id="removeLayer" class="btn btn-danger btn-sm w-100 mb-2">Eliminar Capa</button>
                    <label for="colorPicker" class="form-label mt-2">Color:</label>
                    <input type="color" id="colorPicker" value="#000000" class="mb-2">
                    
                    <div class="d-flex mb-2 w-100">
                        <button id="undo" class="btn btn-warning btn-sm flex-fill me-1">Deshacer</button>
                        <button id="redo" class="btn btn-info btn-sm flex-fill ms-1">Rehacer</button>
                    </div>
                    <button id="download" class="btn btn-primary btn-sm w-100 mb-2">Descargar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<br></br>

<script src="js/pixelart.js"></script>

<?php include __DIR__ . '/layout/footer.php'; ?>