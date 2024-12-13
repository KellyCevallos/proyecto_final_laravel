-- Crear tabla `users`
CREATE TABLE users (
    userId BIGSERIAL PRIMARY KEY, -- Identificador único del usuario
    userName VARCHAR(255) NOT NULL, -- Nombre de usuario
    password VARCHAR(255) NOT NULL, -- Contraseña del usuario
    userNombres VARCHAR(255) NOT NULL, -- Nombres del usuario
    userApellidos VARCHAR(255) NOT NULL, -- Apellidos del usuario
    userCorreo VARCHAR(255) NOT NULL, -- Correo electrónico del usuario
    created_at TIMESTAMP NULL DEFAULT NULL, -- Marca de tiempo de creación
    updated_at TIMESTAMP NULL DEFAULT NULL -- Marca de tiempo de actualización
);

-- Crear tabla `nivel`
CREATE TABLE nivel (
    nivelId BIGSERIAL PRIMARY KEY, -- Identificador único del nivel
    nivelName VARCHAR(255) NOT NULL, -- Nombre del nivel
    created_at TIMESTAMP NULL DEFAULT NULL, -- Marca de tiempo de creación
    updated_at TIMESTAMP NULL DEFAULT NULL -- Marca de tiempo de actualización
);


-- Crear tabla `categorias`
CREATE TABLE categorias (
    categoriaId BIGSERIAL PRIMARY KEY,
    categoriaName VARCHAR(255) NOT NULL,
    categoriaDescripcion VARCHAR(255) NOT NULL,
    categoriaImagen VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Crear tabla `cursos`
CREATE TABLE cursos (
    cursoId BIGSERIAL PRIMARY KEY,
    cursoName TEXT NOT NULL,
    cursoDescripcion TEXT NOT NULL,
    cursoNivelId BIGINT NOT NULL,
    cursoValor DECIMAL(8,2) NOT NULL,
    cursoRequisito TEXT NOT NULL,
    cursoContenido JSON NOT NULL,
    cursoExamen JSON NOT NULL,
    createdBy VARCHAR(255) NOT NULL,
    cursoCategoriaId BIGINT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (cursoNivelId) REFERENCES nivel(nivelId),
    FOREIGN KEY (cursoCategoriaId) REFERENCES categorias(categoriaId)
);

-- Crear tabla `pagoType`
CREATE TABLE pagoType (
    pagoTypeId BIGSERIAL PRIMARY KEY,
    pagoTypeName VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Crear tabla `pagos`
CREATE TABLE pagos (
    pagoId BIGSERIAL PRIMARY KEY,
    pagoMonto DECIMAL(10,2) NOT NULL,
    pagoType_pagoTypeId BIGINT NOT NULL,
    pagoComprobante TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (pagoType_pagoTypeId) REFERENCES pagoType(pagoTypeId)
);

-- Crear tabla `clases`
CREATE TABLE clases (
    claseId BIGSERIAL PRIMARY KEY,
    users_userId BIGINT NOT NULL,
    curso_cursoId BIGINT NOT NULL,
    relNota INT NULL,
    relAprobado BOOLEAN DEFAULT FALSE,
    relVerificacion BOOLEAN DEFAULT FALSE,
    pagoId BIGINT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE (users_userId, curso_cursoId),
    FOREIGN KEY (users_userId) REFERENCES users(userId),
    FOREIGN KEY (curso_cursoId) REFERENCES cursos(cursoId),
    FOREIGN KEY (pagoId) REFERENCES pagos(pagoId)
);

-- Crear tabla `certificados`
CREATE TABLE certificados (
    certificadoId BIGSERIAL PRIMARY KEY,
    ruta VARCHAR(255) NOT NULL,
    clases_users_userId BIGINT NOT NULL,
    clases_curso_cursoId BIGINT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (clases_users_userId) REFERENCES users(userId),
    FOREIGN KEY (clases_curso_cursoId) REFERENCES cursos(cursoId)
);
