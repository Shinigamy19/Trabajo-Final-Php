<?php include __DIR__ . '/layout/header.php'; ?>

<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h2>Subir Archivos</h2>
            <br>
        </div>
    </div>
    <div class="row justify-content-center">
        <!-- Subir Diseño -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-primary h-100">
                <h3 class="card-header">Subí tu Diseño</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="diseñoFile" class="form-label">
                                Subí tus propios diseños para inspirar a otros
                            </label>
                            <input type="file" id="diseñoFile" class="form-control" name="diseñoFile">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Subir Imagen</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Subir Paleta -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-success h-100">
                <h3 class="card-header">Subí tu Paleta</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="paletaFile" class="form-label">
                                Subí tu propia paleta de colores
                            </label>
                            <input type="file" id="paletaFile" class="form-control" name="paletaFile">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Subir paleta</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Subir Audio -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-info h-100">
                <h3 class="card-header">Subí tus audios</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="audioFile" class="form-label">
                                Subí tus propios audios en 8 bits
                            </label>
                            <input type="file" id="audioFile" class="form-control" name="audioFile">
                        </div>
                        <button type="submit" class="btn btn-info w-100">Subir Audio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>