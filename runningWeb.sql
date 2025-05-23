CREATE TABLE Usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    rol VARCHAR(20) NOT NULL DEFAULT 'corredor',
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50),
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(100) NOT NULL,
    sexo VARCHAR(10) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    fecha_registro DATE,
    token_recuperacion VARCHAR(255),
    token_expiracion DATETIME
);

CREATE TABLE Vendedor (
    id_vendedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre_tienda VARCHAR(100) NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id_usuario)
);

CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    fecha_publicacion DATE,
    vendedor_id INT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    stock INT NOT NULL,
    FOREIGN KEY (vendedor_id) REFERENCES Vendedor(usuario_id)
);

CREATE TABLE Fotos_producto (
    id_foto_producto INT PRIMARY KEY AUTO_INCREMENT,
    url_foto VARCHAR(255),
    id_producto INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
);

CREATE TABLE Ciudad (
    id_ciudad INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Ubicacion (
    id_ubicacion INT PRIMARY KEY AUTO_INCREMENT,
    direccion TEXT NOT NULL,
    descripcion TEXT,
    coordenadas VARCHAR(100),
    id_ciudad INT NOT NULL,
    FOREIGN KEY (id_ciudad) REFERENCES Ciudad(id_ciudad)
);

CREATE TABLE Equipo (
    id_equipo INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Corredor (
    id_corredor INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    ciudad_id INT,
    equipo_id INT,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (ciudad_id) REFERENCES Ciudad(id_ciudad),
    FOREIGN KEY (equipo_id) REFERENCES Equipo(id_equipo)
);

CREATE TABLE Compra_usuario (
    id_compra INT PRIMARY KEY AUTO_INCREMENT,
    id_ubicacion_envio INT,
    fecha_compra DATE NOT NULL,
    fecha_entrega_real DATE,
    fecha_envio DATE,
    fecha_entrega_estimada DATE,
    precio_compra DECIMAL(10,2),
    comprador_id INT NOT NULL,
    FOREIGN KEY (id_ubicacion_envio) REFERENCES Ubicacion(id_ubicacion),
    FOREIGN KEY (comprador_id) REFERENCES Corredor(id_corredor)
);

CREATE TABLE Lista_productos_comprados (
    id_lista_compra INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT NOT NULL,
    id_compra INT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES Producto(id_producto),
    FOREIGN KEY (id_compra) REFERENCES Compra_usuario(id_compra)
);

CREATE TABLE Patrocinador (
    id_patrocidador INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    ciudad_id INT,
    FOREIGN KEY (ciudad_id) REFERENCES Ciudad(id_ciudad)
);

CREATE TABLE Evento (
    id_evento INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    descripcion TEXT,
    id_patrocinador INT,
    ubicacion_id INT NOT NULL,
    FOREIGN KEY (id_patrocinador) REFERENCES Patrocinador(id_patrocidador),
    FOREIGN KEY (ubicacion_id) REFERENCES Ubicacion(id_ubicacion)
);

CREATE TABLE Tipo_carrera (
    id_tipo_carrera INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

CREATE TABLE Categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL
);

CREATE TABLE rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    puntos TEXT NOT NULL, -- Almacenará los puntos como JSON
    distancia DECIMAL(10, 2),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE Carrera (
    id_carrera INT PRIMARY KEY AUTO_INCREMENT,
    distancia DECIMAL(6,2) NOT NULL,
    id_evento INT,
    tipo_carrera_id INT,
    id_categoria INT NOT NULL,
    id_ruta INT NOT NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id_evento),
    FOREIGN KEY (tipo_carrera_id) REFERENCES Tipo_carrera(id_tipo_carrera),
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    FOREIGN KEY (id_ruta) REFERENCES Ruta(id)
);

CREATE TABLE Participacion_evento (
    usuario_id INT NOT NULL,
    evento_id INT NOT NULL,
    PRIMARY KEY (usuario_id, evento_id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (evento_id) REFERENCES Evento(id_evento)
);