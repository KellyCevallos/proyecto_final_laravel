
Tabla: cursos (Tabla Maestra)
	Descripción: Almacena la información principal de cada curso disponible en la plataforma.
	Columnas:
		cursoId (PK): Identificador único del curso.
		cursoName: Nombre del curso.
		cursoDescripcion: Descripción del curso.
		cursoNivelId (FK): Relación con la tabla nivel que indica el nivel de dificultad del curso.
		cursoValor: Costo del curso.
		cursoRequisito: Requisitos previos para inscribirse en el curso.
		cursoContenido: Contenido detallado del curso.
		cursoCategoriaId (FK): Relación con la tabla categorias que indica a qué categoría pertenece el curso.

Tabla: categorias
	Descripción: Define las categorías a las que pertenecen los cursos.
	Columnas:
		categoriaId (PK): Identificador único de la categoría.
		categoriaName: Nombre de la categoría.

Tabla: nivel
	Descripción: Especifica los niveles de los cursos (por ejemplo, básico, intermedio, avanzado).
	Columnas:
		nivelId (PK): Identificador único del nivel.
		nivelName: Nombre del nivel.

Tabla: users (Usuarios)
	Descripción: Almacena los datos de los usuarios registrados en el sistema.
	Columnas:
		userId (PK): Identificador único del usuario.
		userName: Nombre del usuario.
		userPassword: Contraseña del usuario.
		userTypeId (FK): Relación con la tabla usersType para especificar el tipo de usuario (por ejemplo, administrador, estudiante)

Tabla usersType (Tipos de Usuario)
	Descripción: Define los tipos de usuarios.
	Columnas:
		userTypeId (PK): Identificador único del tipo de usuario.
		userType: Tipo de usuario.

Tabla clases
	Descripción: Registra las clases en las que se inscriben los usuarios.
	Columnas:
		users_userId (FK): Relación con la tabla users.
		curso_cursoId (FK): Relación con la tabla cursos.
		relNota: Nota obtenida por el usuario en la clase.
		relAprobado: Indica si el usuario aprobó el curso.
		relPrueba: Se guarda el contenido de una prueba
		relVerificacion: Verificación de pago.
		relCode: Código de la clase.

Tabla pagos
	Descripción: Almacena la información de los pagos realizados por los usuarios para los cursos.
	Columnas:
		pagoId (PK): Identificador único del pago.
		pagoMonto: Monto pagado.
		clases_users_userId (FK): Relación con el userId de la tabla clases.
		clases_curso_cursoId (FK): Relación con el cursoId de la tabla clases.
		pagoType_pagoTypeId (FK): Relación con la tabla pagoType.

Tabla pagoType
	Descripción: Define los diferentes tipos de pago.
	Columnas:
		pagoTypeId (PK): Identificador único del tipo de pago.
		pagoTypeName: Nombre del tipo de pago (por ejemplo, tarjeta de crédito, PayPal).

Tabla certificados
	Descripción: Almacena los certificados generados para los usuarios que completan los cursos.
	Columnas:
		certificadoId (PK): Identificador único del certificado.
		ruta: Ruta del archivo del certificado.
		clases_users_userId (FK): Relación con la tabla clases (usuario).
		clases_curso_cursoId (FK): Relación con la tabla clases (curso).

Relación entre tablas:
	cursos está relacionado con nivel y categorias para clasificar los cursos.
	users y clases manejan la inscripción de los usuarios en los cursos.
	pagos almacena los pagos realizados por los usuarios para inscribirse en las clases.
	certificados se generan al completar con éxito un curso.
	clases tabla intermedia entre cursos y usuarios



