const defaultSize = 32;
let canvasSize = defaultSize;
let layers = [];
let activeLayer = 0;
let isDrawing = false;
let color = "#000000";

// Historial para deshacer/rehacer por capa
let history = [[]];
let redoStack = [[]];

function createLayer(size, opacity = 0.5) {
    const canvas = document.createElement('canvas');
    canvas.width = size;
    canvas.height = size;
    canvas.className = 'pixel-canvas position-absolute';
    canvas.dataset.opacity = opacity;
    return canvas;
}

function updateLayerSelect() {
    const select = document.getElementById('layerSelect');
    select.innerHTML = '';
    layers.forEach((_, i) => {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = 'Capa ' + (i + 1);
        if (i === activeLayer) opt.selected = true;
        select.appendChild(opt);
    });
}

function renderCanvas() {
    const container = document.getElementById('pixelCanvasContainer');
    container.innerHTML = '';
    container.style.position = 'relative';
    // El tamaño visual del canvas depende del tamaño del pixel y la cantidad de pixeles
    const pixelSize = 16;
    container.style.width = (canvasSize * pixelSize) + 'px';
    container.style.height = (canvasSize * pixelSize) + 'px';
    
    // --- Grilla SVG encima ---
    let grid = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    grid.setAttribute("width", canvasSize * pixelSize);
    grid.setAttribute("height", canvasSize * pixelSize);
    grid.style.position = "absolute";
    grid.style.left = "0";
    grid.style.top = "0";
    grid.style.pointerEvents = "none";
    for (let i = 0; i <= canvasSize; i++) {
        // Líneas verticales
        let vline = document.createElementNS("http://www.w3.org/2000/svg", "line");
        vline.setAttribute("x1", i * pixelSize);
        vline.setAttribute("y1", 0);
        vline.setAttribute("x2", i * pixelSize);
        vline.setAttribute("y2", canvasSize * pixelSize);
        vline.setAttribute("stroke", "#bbb");
        vline.setAttribute("stroke-width", "1");
        grid.appendChild(vline);
        // Líneas horizontales
        let hline = document.createElementNS("http://www.w3.org/2000/svg", "line");
        hline.setAttribute("x1", 0);
        hline.setAttribute("y1", i * pixelSize);
        hline.setAttribute("x2", canvasSize * pixelSize);
        hline.setAttribute("y2", i * pixelSize);
        hline.setAttribute("stroke", "#bbb");
        hline.setAttribute("stroke-width", "1");
        grid.appendChild(hline);
    }

    // Preview combinada de todas las capas (papel cebolla)
    const preview = document.createElement('canvas');
    preview.width = canvasSize;
    preview.height = canvasSize;
    preview.style.width = (canvasSize * pixelSize) + 'px';
    preview.style.height = (canvasSize * pixelSize) + 'px';
    preview.style.position = 'absolute';
    preview.style.left = '0';
    preview.style.top = '0';
    preview.className = 'pixel-canvas';
    const ctx = preview.getContext('2d');
    layers.forEach((layer, i) => {
        ctx.globalAlpha = (i === activeLayer) ? 1 : 0.4;
        ctx.drawImage(layer, 0, 0);
    });
    ctx.globalAlpha = 1;
    container.appendChild(preview);

    // Canvas invisible encima para dibujar sobre la capa activa
    const drawCanvas = layers[activeLayer];
    drawCanvas.style.width = (canvasSize * pixelSize) + 'px';
    drawCanvas.style.height = (canvasSize * pixelSize) + 'px';
    drawCanvas.style.position = 'absolute';
    drawCanvas.style.left = '0';
    drawCanvas.style.top = '0';
    drawCanvas.style.opacity = 0; // invisible, solo para eventos
    container.appendChild(drawCanvas);

    // Agrega la grilla encima de todo
    container.appendChild(grid);
}

function saveState() {
    if (!history[activeLayer]) history[activeLayer] = [];
    if (!redoStack[activeLayer]) redoStack[activeLayer] = [];
    const ctx = layers[activeLayer].getContext('2d');
    history[activeLayer].push(ctx.getImageData(0, 0, canvasSize, canvasSize));
    if (history[activeLayer].length > 50) history[activeLayer].shift();
    redoStack[activeLayer] = [];
}

function undo() {
    if (history[activeLayer] && history[activeLayer].length > 0) {
        const ctx = layers[activeLayer].getContext('2d');
        redoStack[activeLayer].push(ctx.getImageData(0, 0, canvasSize, canvasSize));
        const prev = history[activeLayer].pop();
        ctx.putImageData(prev, 0, 0);
        renderCanvas();
    }
}

function redo() {
    if (redoStack[activeLayer] && redoStack[activeLayer].length > 0) {
        const ctx = layers[activeLayer].getContext('2d');
        history[activeLayer].push(ctx.getImageData(0, 0, canvasSize, canvasSize));
        const next = redoStack[activeLayer].pop();
        ctx.putImageData(next, 0, 0);
        renderCanvas();
    }
}

function resizeAllLayers(newSize) {
    layers = layers.map(layer => {
        const newLayer = createLayer(newSize);
        const ctx = newLayer.getContext('2d');
        ctx.drawImage(layer, 0, 0, newSize, newSize);
        return newLayer;
    });
    canvasSize = newSize;
    history = layers.map(() => []);
    redoStack = layers.map(() => []);
    renderCanvas();
    updateLayerSelect();
}

function addLayer() {
    const layer = createLayer(canvasSize);
    layers.push(layer);
    history.push([]);
    redoStack.push([]);
    activeLayer = layers.length - 1;
    updateLayerSelect();
    renderCanvas();
    setupDrawing(layer);
}

function removeLayer() {
    if (layers.length > 1) {
        layers.splice(activeLayer, 1);
        history.splice(activeLayer, 1);
        redoStack.splice(activeLayer, 1);
        activeLayer = Math.max(0, activeLayer - 1);
        updateLayerSelect();
        renderCanvas();
    }
}

function setActiveLayer(idx) {
    activeLayer = idx;
    if (!history[activeLayer]) history[activeLayer] = [];
    if (!redoStack[activeLayer]) redoStack[activeLayer] = [];
    renderCanvas();
}

function setupDrawing(layer) {
    layer.onmousedown = e => {
        saveState();
        isDrawing = true;
        drawPixel(e, layer);
    };
    layer.onmousemove = e => {
        if (isDrawing) drawPixel(e, layer);
    };
    layer.onmouseup = () => isDrawing = false;
    layer.onmouseleave = () => isDrawing = false;
    layer.ontouchstart = e => {
        saveState();
        isDrawing = true;
        drawPixel(e, layer);
    };
    layer.ontouchmove = e => {
        if (isDrawing) drawPixel(e, layer);
    };
    layer.ontouchend = () => isDrawing = false;
}

function drawPixel(e, layer) {
    const rect = layer.getBoundingClientRect();
    const x = Math.floor(((e.touches ? e.touches[0].clientX : e.clientX) - rect.left) / (rect.width / canvasSize));
    const y = Math.floor(((e.touches ? e.touches[0].clientY : e.clientY) - rect.top) / (rect.height / canvasSize));
    if (x >= 0 && y >= 0 && x < canvasSize && y < canvasSize) {
        const ctx = layer.getContext('2d');
        ctx.fillStyle = color;
        ctx.fillRect(x, y, 1, 1);
        renderCanvas();
    }
}

function downloadImage() {
    // Combina todas las capas en una sola imagen
    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = canvasSize;
    finalCanvas.height = canvasSize;
    const ctx = finalCanvas.getContext('2d');
    layers.forEach(layer => {
        ctx.drawImage(layer, 0, 0);
    });
    // Escala para que se vea grande al descargar
    const exportCanvas = document.createElement('canvas');
    exportCanvas.width = canvasSize * 16;
    exportCanvas.height = canvasSize * 16;
    exportCanvas.getContext('2d').imageSmoothingEnabled = false;
    exportCanvas.getContext('2d').drawImage(finalCanvas, 0, 0, exportCanvas.width, exportCanvas.height);
    const link = document.createElement('a');
    link.download = 'pixelart.png';
    link.href = exportCanvas.toDataURL();
    link.click();
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    // Crear la primera capa
    layers = [createLayer(defaultSize)];
    history = [[]];
    redoStack = [[]];
    setupDrawing(layers[0]);
    renderCanvas();
    updateLayerSelect();

    document.getElementById('canvasSize').addEventListener('change', function() {
        const newSize = parseInt(this.value);
        resizeAllLayers(newSize);
        layers.forEach(setupDrawing);
    });

    document.getElementById('addLayer').addEventListener('click', () => {
        addLayer();
    });

    document.getElementById('removeLayer').addEventListener('click', () => {
        removeLayer();
    });

    document.getElementById('layerSelect').addEventListener('change', function() {
        setActiveLayer(parseInt(this.value));
    });

    document.getElementById('colorPicker').addEventListener('input', function() {
        color = this.value;
    });

    document.getElementById('download').addEventListener('click', downloadImage);

    
    document.getElementById('undo').addEventListener('click', undo);
    document.getElementById('redo').addEventListener('click', redo);
});