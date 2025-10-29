CREATE DATABASE IF NOT EXISTS halloween CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE halloween;

-- Tabla disfraces
CREATE TABLE IF NOT EXISTS disfraces (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  descripcion TEXT NOT NULL,
  votos INT(11) NOT NULL DEFAULT 0,
  foto VARCHAR(255) NOT NULL,
  foto_blob LONGBLOB NOT NULL,
  eliminado INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  clave TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla votos (para prevenir duplicados, unique sobre usuario+disfraz)
CREATE TABLE IF NOT EXISTS votos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_usuario INT(11) NOT NULL,
  id_disfraz INT(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY unico_voto (id_usuario,id_disfraz),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (id_disfraz) REFERENCES disfraces(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;