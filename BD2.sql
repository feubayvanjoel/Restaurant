
/*! SQL mis à jour pour le système de gestion de restaurant
   Améliorations apportées :
   1) Utilisation d'InnoDB et utf8mb4 pour toutes les tables pour l'intégrité transactionnelle et le support Unicode.
   2) Remplacement de TEXT par VARCHAR(longueur) approprié et utilisation de DECIMAL pour les valeurs monétaires.
   3) Ajout de contraintes UNIQUE là où c'est pertinent (logins, numéros de table, ticket par commande).
   4) Ajout d'INDEX utiles pour la performance sur les colonnes FK et les champs fréquemment interrogés.
   5) Ajout de ON DELETE CASCADE pour les tables enfants afin d'assurer un nettoyage automatique et sécurisé.
   6) Correction de la logique des triggers (suppression des références à des colonnes inexistantes).
   7) Conservation des procédures et triggers pour la gestion du stock et des réservations, adaptés au schéma corrigé.
   8) Colonnes PASSWORD_ prévues pour stocker des hash bcrypt (VARCHAR(255)) et utilisation de valeurs fictives dans les exemples ; là il est recommandé d'utiliser des seeders Laravel pour générer les hash.
*/

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS COMPOSER;
DROP TABLE IF EXISTS CONTENIR;
DROP TABLE IF EXISTS HORAIRE_RESERVATION;
DROP TABLE IF EXISTS TICKET;
DROP TABLE IF EXISTS COMMANDE;
DROP TABLE IF EXISTS GESTION_SALLE;
DROP TABLE IF EXISTS COMPTE;
DROP TABLE IF EXISTS PERSONNEL;
DROP TABLE IF EXISTS CLIENT;
DROP TABLE IF EXISTS BOISSONS;
DROP TABLE IF EXISTS PLATS;
DROP TABLE IF EXISTS RESERVATION; -- Cleanup old table if exists
DROP TABLE IF EXISTS ADMINISTRATEUR; -- Cleanup old table if exists
DROP TABLE IF EXISTS ADRESSE; -- Cleanup old table if exists
DROP TABLE IF EXISTS ACCES; -- Cleanup old table if exists
DROP TABLE IF EXISTS GERER; -- Cleanup old table if exists
DROP TABLE IF EXISTS POSSEDER; -- Cleanup old table if exists




-- Table : BOISSONS
CREATE TABLE BOISSONS
(
   IDBOISSONS          INT NOT NULL AUTO_INCREMENT,
   NOM                 VARCHAR(150) NOT NULL,
   QUANTITE            INT DEFAULT 0,
   PRIX                DECIMAL(10,2) DEFAULT 0.00,
   PRIMARY KEY (IDBOISSONS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : PLATS
CREATE TABLE PLATS
(
   IDPLATS             INT NOT NULL AUTO_INCREMENT,
   NOM                 VARCHAR(150) NOT NULL,
   QUANTITE            INT DEFAULT 0,
   PRIX                DECIMAL(10,2) DEFAULT 0.00,
   PRIMARY KEY (IDPLATS)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : PERSONNEL
CREATE TABLE PERSONNEL
(
   IDPERSONNEL         INT NOT NULL AUTO_INCREMENT,
   NOM                 VARCHAR(100),
   PRENOM              VARCHAR(100),
   POSTE               ENUM('ADMIN', 'CAISSIER', 'CUISINIER', 'SERVEUR') NOT NULL,
   EMAIL               VARCHAR(100),
   NUMERO              VARCHAR(50),
   ADRESSE             VARCHAR(255),
   PRIMARY KEY (IDPERSONNEL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : CLIENT
CREATE TABLE CLIENT
(
   IDCLIENT            INT NOT NULL AUTO_INCREMENT,
   NOM                 VARCHAR(100),
   PRENOM              VARCHAR(100),
   EMAIL               VARCHAR(100),
   NUMERO              VARCHAR(50),
   ADRESSE             VARCHAR(255),
   PRIMARY KEY (IDCLIENT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : COMPTE
CREATE TABLE COMPTE
(
   LOGIN               VARCHAR(100) NOT NULL,
   PASSWORD            VARCHAR(255),
   IDPROPRIETAIRE      INT NOT NULL, -- IDPERSONNEL ou IDCLIENT
   ROLE                ENUM('ADMIN', 'CAISSIER', 'CUISINIER', 'SERVEUR', 'CLIENT') NOT NULL,
   CREER_LE            DATETIME DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (LOGIN),
   INDEX IDX_COMPTE_PROPRIETAIRE (IDPROPRIETAIRE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : GESTION_SALLE
CREATE TABLE GESTION_SALLE
(
   IDTABLE             INT NOT NULL AUTO_INCREMENT,
   NUMERO              INT,
   STATUT              VARCHAR(50) DEFAULT 'Libre',
   PRIMARY KEY (IDTABLE),
   UNIQUE KEY UK_GESTION_SALLE_NUMERO (NUMERO)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : COMMANDE
CREATE TABLE COMMANDE
(
   IDCOMMANDE          INT NOT NULL AUTO_INCREMENT,
   IDTABLE             INT NOT NULL,
   IDCLIENT            INT NOT NULL,
   HORAIRE             DATETIME DEFAULT CURRENT_TIMESTAMP,
   STATUT              VARCHAR(50) DEFAULT 'Confirmee',
   PRIMARY KEY (IDCOMMANDE),
   INDEX IDX_COMMANDE_TABLE (IDTABLE),
   INDEX IDX_COMMANDE_CLIENT (IDCLIENT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : COMPOSER (plats dans une commande)
CREATE TABLE COMPOSER
(
   IDPLATS            INT NOT NULL,
   IDCOMMANDE         INT NOT NULL,
   NBPLATS            INT DEFAULT 1,
   PRIMARY KEY (IDPLATS, IDCOMMANDE),
   INDEX IDX_COMPOSER_COMMANDE (IDCOMMANDE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : CONTENIR (boissons dans une commande)
CREATE TABLE CONTENIR
(
   IDBOISSONS         INT NOT NULL,
   IDCOMMANDE         INT NOT NULL,
   NBBOISSONS         INT DEFAULT 1,
   PRIMARY KEY (IDBOISSONS, IDCOMMANDE),
   INDEX IDX_CONTENIR_COMMANDE (IDCOMMANDE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : HORAIRE_RESERVATION
CREATE TABLE HORAIRE_RESERVATION
(
   IDHORAIRERESERVATION INT NOT NULL AUTO_INCREMENT,
   IDCLIENT             INT NOT NULL,
   STATUT               ENUM('ACTIVE', 'ANNULEE', 'TERNINEE', 'SUPPRIMEE') DEFAULT 'ACTIVE',
   ECHEANCE             DATETIME,
   NOMBRE_PERSONNE      INT,
   DATE_DEBUT           DATETIME,
   DATE_FIN             DATETIME,
   CREER_LE             DATETIME DEFAULT CURRENT_TIMESTAMP,
   IDTABLE              INT, -- Ajouté pour garder la logique de réservation de table
   PRIMARY KEY (IDHORAIRERESERVATION),
   INDEX IDX_HORAIRE_RESERVATION_CLIENT (IDCLIENT),
   INDEX IDX_HORAIRE_RESERVATION_TABLE (IDTABLE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : TICKET
CREATE TABLE TICKET
(
   IDTICKET           INT NOT NULL AUTO_INCREMENT,
   IDCOMMANDE         INT NOT NULL,
   PRIX               DECIMAL(12,2) DEFAULT 0.00,
   DATETICKET         DATETIME DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (IDTICKET),
   UNIQUE KEY UK_TICKET_COMMANDE (IDCOMMANDE),
   INDEX IDX_TICKET_COMMANDE (IDCOMMANDE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Foreign keys and constraints

ALTER TABLE COMMANDE ADD CONSTRAINT FK_COMMANDE_TABLE FOREIGN KEY (IDTABLE)
      REFERENCES GESTION_SALLE (IDTABLE) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE COMMANDE ADD CONSTRAINT FK_COMMANDE_CLIENT FOREIGN KEY (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE COMPOSER ADD CONSTRAINT FK_COMPOSER_PLATS FOREIGN KEY (IDPLATS)
      REFERENCES PLATS (IDPLATS) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE COMPOSER ADD CONSTRAINT FK_COMPOSER_COMMANDE FOREIGN KEY (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE CONTENIR ADD CONSTRAINT FK_CONTENIR_BOISSONS FOREIGN KEY (IDBOISSONS)
      REFERENCES BOISSONS (IDBOISSONS) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE CONTENIR ADD CONSTRAINT FK_CONTENIR_COMMANDE FOREIGN KEY (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE HORAIRE_RESERVATION ADD CONSTRAINT FK_HORAIRE_RESERVATION_CLIENT FOREIGN KEY (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ON DELETE CASCADE ON UPDATE CASCADE;
      
ALTER TABLE HORAIRE_RESERVATION ADD CONSTRAINT FK_HORAIRE_RESERVATION_TABLE FOREIGN KEY (IDTABLE)
      REFERENCES GESTION_SALLE (IDTABLE) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE TICKET ADD CONSTRAINT FK_TICKET_COMMANDE FOREIGN KEY (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ON DELETE CASCADE ON UPDATE CASCADE;

-- Note: COMPTE.IDPROPRIETAIRE cannot have a simple FK because it points to either PERSONNEL or CLIENT.
-- We will handle integrity via application logic or triggers if strictly necessary.
-- For now, no SQL FK on IDPROPRIETAIRE to allow polymorphism.

-- Procédures pour le statut des tables, le recalcul des tickets et la vérification des réservations expirées
DELIMITER $$
CREATE PROCEDURE UpdateTableStatus(
    IN p_IDTABLE INT,
    IN p_STATUT VARCHAR(50)
)
BEGIN
    UPDATE GESTION_SALLE
    SET STATUT = p_STATUT
    WHERE IDTABLE = p_IDTABLE;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE RecalculateTicket(
    IN p_IDCOMMANDE INT
)
BEGIN
    DECLARE total_plats DECIMAL(12,2) DEFAULT 0.00;
    DECLARE total_boissons DECIMAL(12,2) DEFAULT 0.00;
    DECLARE v_total DECIMAL(12,2) DEFAULT 0.00;

    SELECT IFNULL(SUM(P.PRIX * C.NBPLATS),0) INTO total_plats
      FROM COMPOSER C
      JOIN PLATS P ON C.IDPLATS = P.IDPLATS
      WHERE C.IDCOMMANDE = p_IDCOMMANDE;

    SELECT IFNULL(SUM(B.PRIX * CO.NBBOISSONS),0) INTO total_boissons
      FROM CONTENIR CO
      JOIN BOISSONS B ON CO.IDBOISSONS = B.IDBOISSONS
      WHERE CO.IDCOMMANDE = p_IDCOMMANDE;

    SET v_total = total_plats + total_boissons;

    UPDATE TICKET 
      SET PRIX = v_total 
      WHERE IDCOMMANDE = p_IDCOMMANDE;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE CheckExpiredReservations()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE expired_table_id INT;

    DECLARE cur CURSOR FOR
        SELECT DISTINCT IDTABLE
        FROM HORAIRE_RESERVATION
        WHERE STATUT = 'ACTIVE'
          AND ECHEANCE < NOW()
          AND IDTABLE IS NOT NULL;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO expired_table_id;
        IF done THEN
            LEAVE read_loop;
        END IF;

        IF NOT EXISTS (
            SELECT 1 FROM HORAIRE_RESERVATION
            WHERE IDTABLE = expired_table_id
              AND STATUT = 'ACTIVE'
              AND ECHEANCE >= NOW()
        ) THEN
            CALL UpdateTableStatus(expired_table_id, 'Libre');
        END IF;

    END LOOP;

    CLOSE cur;
END$$
DELIMITER ;

-- TRIGGERS

DELIMITER $$
-- Triggers CONTENIR pour ajuster le stock et recalculer le ticket
CREATE TRIGGER after_insert_CONTENIR
AFTER INSERT ON CONTENIR
FOR EACH ROW
BEGIN
    UPDATE BOISSONS
      SET QUANTITE = QUANTITE - NEW.NBBOISSONS
      WHERE IDBOISSONS = NEW.IDBOISSONS;

    CALL RecalculateTicket(NEW.IDCOMMANDE);
END$$

CREATE TRIGGER after_update_CONTENIR
AFTER UPDATE ON CONTENIR
FOR EACH ROW
BEGIN
    UPDATE BOISSONS
      SET QUANTITE = QUANTITE + (OLD.NBBOISSONS - NEW.NBBOISSONS)
      WHERE IDBOISSONS = NEW.IDBOISSONS;

    CALL RecalculateTicket(NEW.IDCOMMANDE);
END$$

CREATE TRIGGER after_delete_CONTENIR
AFTER DELETE ON CONTENIR
FOR EACH ROW
BEGIN
    UPDATE BOISSONS
      SET QUANTITE = QUANTITE + OLD.NBBOISSONS
      WHERE IDBOISSONS = OLD.IDBOISSONS;

    CALL RecalculateTicket(OLD.IDCOMMANDE);
END$$

-- Triggers COMPOSER pour ajuster le stock et recalculer le ticket
CREATE TRIGGER after_insert_COMPOSER
AFTER INSERT ON COMPOSER
FOR EACH ROW
BEGIN
    UPDATE PLATS
      SET QUANTITE = QUANTITE - NEW.NBPLATS
      WHERE IDPLATS = NEW.IDPLATS;

    CALL RecalculateTicket(NEW.IDCOMMANDE);
END$$

CREATE TRIGGER after_update_COMPOSER
AFTER UPDATE ON COMPOSER
FOR EACH ROW
BEGIN
    UPDATE PLATS
      SET QUANTITE = QUANTITE + (OLD.NBPLATS - NEW.NBPLATS)
      WHERE IDPLATS = NEW.IDPLATS;

    CALL RecalculateTicket(NEW.IDCOMMANDE);
END$$

CREATE TRIGGER after_delete_COMPOSER
AFTER DELETE ON COMPOSER
FOR EACH ROW
BEGIN
    UPDATE PLATS
      SET QUANTITE = QUANTITE + OLD.NBPLATS
      WHERE IDPLATS = OLD.IDPLATS;

    CALL RecalculateTicket(OLD.IDCOMMANDE);
END$$

-- Triggers CLIENT : à l'insertion, créer un compte client par défaut ? 
-- Le prompt dit "Un personnel pourra avoir plusieurs comptes".
-- Pour le client, on peut imaginer un compte par défaut ou laisser l'app le gérer.
-- On supprime l'ancien trigger after_insert_CLIENT qui créait POSSEDER.

CREATE TRIGGER before_delete_CLIENT
BEFORE DELETE ON CLIENT
FOR EACH ROW
BEGIN
  -- Supprimer les commandes explicitement pour garantir l'exécution des triggers et la restauration du stock si nécessaire
    DELETE FROM COMMANDE WHERE IDCLIENT = OLD.IDCLIENT;
    -- HORAIRE_RESERVATION will be removed by FK ON DELETE CASCADE
    -- COMPTE linked to client needs manual cleanup if no FK
    DELETE FROM COMPTE WHERE IDPROPRIETAIRE = OLD.IDCLIENT AND ROLE = 'CLIENT';
END$$

-- Triggers COMMANDE pour la disponibilité des tables et la génération des tickets
CREATE TRIGGER before_insert_COMMANDE
BEFORE INSERT ON COMMANDE
FOR EACH ROW
BEGIN
    DECLARE table_statut VARCHAR(50);
    DECLARE reservation_client_id INT DEFAULT NULL;

    -- Update expired reservations before checking availability
    CALL CheckExpiredReservations();

    SELECT STATUT INTO table_statut
    FROM GESTION_SALLE
    WHERE IDTABLE = NEW.IDTABLE
    FOR UPDATE;

    IF table_statut = 'Reservee' THEN
        SELECT IDCLIENT INTO reservation_client_id
        FROM HORAIRE_RESERVATION
        WHERE IDTABLE = NEW.IDTABLE
          AND STATUT = 'ACTIVE'
          AND ECHEANCE >= NOW()
        ORDER BY DATE_DEBUT DESC
        LIMIT 1;

        IF reservation_client_id IS NULL OR reservation_client_id <> NEW.IDCLIENT THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La table n''est pas libre.';
        END IF;
    ELSEIF table_statut <> 'Libre' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La table n''est pas libre.';
    END IF;

    IF NEW.STATUT IN ('Terminee','Annulee','Supprimee') THEN
        CALL UpdateTableStatus(NEW.IDTABLE, 'Libre');
    ELSE
        CALL UpdateTableStatus(NEW.IDTABLE, 'Occupee');
    END IF;
END$$

CREATE TRIGGER after_insert_COMMANDE
AFTER INSERT ON COMMANDE
FOR EACH ROW
BEGIN
    INSERT INTO TICKET (IDCOMMANDE, PRIX)
    VALUES (NEW.IDCOMMANDE, 0.00);
END$$

CREATE TRIGGER before_update_COMMANDE
BEFORE UPDATE ON COMMANDE
FOR EACH ROW
BEGIN
    DECLARE table_statut VARCHAR(50);

    CALL CheckExpiredReservations();

    IF NEW.STATUT IS NOT NULL THEN
       IF NEW.STATUT IN ('Terminee','Annulee','Supprimee') THEN
          IF NEW.IDTABLE IS NOT NULL THEN
             CALL UpdateTableStatus(NEW.IDTABLE, 'Libre');
          ELSE
             CALL UpdateTableStatus(OLD.IDTABLE, 'Libre');
          END IF;
       END IF;
    END IF;

    IF NEW.IDTABLE IS NOT NULL THEN
        SELECT STATUT INTO table_statut
          FROM GESTION_SALLE
          WHERE IDTABLE = NEW.IDTABLE
          FOR UPDATE;

        IF table_statut <> 'Libre' THEN
           SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La table n''est pas libre.';
        ELSE
           CALL UpdateTableStatus(NEW.IDTABLE, 'Occupee');
        END IF;
    END IF;
END$$

CREATE TRIGGER before_delete_COMMANDE
BEFORE DELETE ON COMMANDE
FOR EACH ROW
BEGIN
    DECLARE v_nbplats INT;
    DECLARE v_idplats INT;
    DECLARE v_nbboissons INT;
    DECLARE v_idboissons INT;
    DECLARE done INT DEFAULT 0;

    DECLARE plats_cursor CURSOR FOR 
         SELECT IDPLATS, NBPLATS FROM COMPOSER WHERE IDCOMMANDE = OLD.IDCOMMANDE;

    DECLARE boissons_cursor CURSOR FOR 
         SELECT IDBOISSONS, NBBOISSONS FROM CONTENIR WHERE IDCOMMANDE = OLD.IDCOMMANDE;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    CALL CheckExpiredReservations();

    -- Liberer la table occupee par la commande
    UPDATE GESTION_SALLE
      SET STATUT = 'Libre'
      WHERE IDTABLE = OLD.IDTABLE;

    -- Retablir les quantites de plats
    SET done = 0;
    OPEN plats_cursor;
    read_loop_plats: LOOP
         FETCH plats_cursor INTO v_idplats, v_nbplats;
         IF done THEN
              LEAVE read_loop_plats;
         END IF;
         UPDATE PLATS
           SET QUANTITE = QUANTITE + v_nbplats
           WHERE IDPLATS = v_idplats;
    END LOOP;
    CLOSE plats_cursor;

    -- Retablir les quantites de boissons
    SET done = 0;
    OPEN boissons_cursor;
    read_loop_boissons: LOOP
         FETCH boissons_cursor INTO v_idboissons, v_nbboissons;
         IF done THEN
              LEAVE read_loop_boissons;
         END IF;
         UPDATE BOISSONS
           SET QUANTITE = QUANTITE + v_nbboissons
           WHERE IDBOISSONS = v_idboissons;
    END LOOP;
    CLOSE boissons_cursor;

    -- Suppressions des entrées liées (COMPOSER, CONTENIR, TICKET) sont gérées par FK ON DELETE CASCADE ou ici explicitement si nécessaire.
END$$

CREATE TRIGGER after_delete_COMMANDE
AFTER DELETE ON COMMANDE
FOR EACH ROW
BEGIN
    CALL CheckExpiredReservations();
    UPDATE GESTION_SALLE
      SET STATUT = 'Libre'
      WHERE IDTABLE = OLD.IDTABLE;
END$$

-- Triggers HORAIRE_RESERVATION
CREATE TRIGGER before_insert_HORAIRE_RESERVATION
BEFORE INSERT ON HORAIRE_RESERVATION
FOR EACH ROW
BEGIN
    DECLARE table_statut VARCHAR(50);

    CALL CheckExpiredReservations();

    IF NEW.IDTABLE IS NOT NULL THEN
        SELECT STATUT INTO table_statut
          FROM GESTION_SALLE
          WHERE IDTABLE = NEW.IDTABLE
          FOR UPDATE;

        IF table_statut <> 'Libre' THEN
           SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La table n''est pas libre.';
        END IF;

        IF NEW.STATUT IN ('TERNINEE','ANNULEE','SUPPRIMEE') THEN
           CALL UpdateTableStatus(NEW.IDTABLE, 'Libre');
        ELSE
           CALL UpdateTableStatus(NEW.IDTABLE, 'Reservee');
        END IF;
    END IF;
END$$

CREATE TRIGGER before_update_HORAIRE_RESERVATION
BEFORE UPDATE ON HORAIRE_RESERVATION
FOR EACH ROW
BEGIN
    DECLARE table_statut VARCHAR(50);

    CALL CheckExpiredReservations();

    IF NEW.IDTABLE IS NOT NULL THEN
        SELECT STATUT INTO table_statut
          FROM GESTION_SALLE
          WHERE IDTABLE = NEW.IDTABLE
          FOR UPDATE;

        IF table_statut <> 'Libre' THEN
           SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La table n''est pas libre.';
        ELSE
           CALL UpdateTableStatus(NEW.IDTABLE, 'Reservee');
        END IF;
    END IF;

    IF NEW.STATUT IS NOT NULL THEN
       IF NEW.STATUT IN ('TERNINEE','ANNULEE','SUPPRIMEE') THEN
          IF NEW.IDTABLE IS NOT NULL THEN
             CALL UpdateTableStatus(NEW.IDTABLE, 'Libre');
          ELSE
             CALL UpdateTableStatus(OLD.IDTABLE, 'Libre');
          END IF;
       END IF;
    END IF;
END$$

CREATE TRIGGER after_delete_HORAIRE_RESERVATION
AFTER DELETE ON HORAIRE_RESERVATION
FOR EACH ROW
BEGIN
    CALL CheckExpiredReservations();
    IF OLD.IDTABLE IS NOT NULL THEN
        UPDATE GESTION_SALLE
          SET STATUT = 'Libre'
          WHERE IDTABLE = OLD.IDTABLE;
    END IF;
END$$

-- Triggers PERSONNEL
CREATE TRIGGER before_delete_PERSONNEL
BEFORE DELETE ON PERSONNEL
FOR EACH ROW
BEGIN
    -- Clean up accounts linked to this personnel
    DELETE FROM COMPTE WHERE IDPROPRIETAIRE = OLD.IDPERSONNEL AND ROLE <> 'CLIENT';
END$$

DELIMITER ;

-- Données d'exemple


INSERT INTO GESTION_SALLE (NUMERO, STATUT) VALUES 
(1, 'Libre'),(2, 'Occupee'),(3, 'Libre'),(4, 'Libre'),(5, 'Occupee'),
(6, 'Libre'),(7, 'Libre'),(8, 'Libre'),(9, 'Libre'),(10, 'Libre'),
(11, 'Libre'),(12, 'Libre'),(13, 'Libre'),(14, 'Libre'),(15, 'Libre'),
(16, 'Libre'),(17, 'Libre'),(18, 'Libre'),(19, 'Libre'),(20, 'Libre');

-- Personnel
INSERT INTO PERSONNEL (NOM, PRENOM, POSTE, EMAIL, NUMERO, ADRESSE) VALUES
('Admin', 'System', 'ADMIN', 'admin@restaurant.com', '+32 470 12 34 56', 'Server Room'),
('Chef', 'Cuisinier', 'CUISINIER', 'chef@restaurant.com', '+32 471 23 45 67', 'Kitchen'),
('Serveur', 'Jean', 'SERVEUR', 'serveur@restaurant.com', '+32 472 34 56 78', 'Dining Hall'),
('Caissier', 'Paul', 'CAISSIER', 'caissier@restaurant.com', '+32 473 45 67 89', 'Front Desk');

-- Clients
INSERT INTO CLIENT (NOM, PRENOM, EMAIL, NUMERO, ADRESSE) VALUES
('Feuba', 'Yvan', 'feuba@email.com', '+32 474 12 34 56', 'Rue de Rivoli, Paris'),
('Ambassa', 'Joel', 'joel@email.com', '+32 475 23 45 67', 'Rue de la Republique, Lyon'),
('Durand', 'Pierre', 'durand@email.com', '+32 476 34 56 78', 'Avenue de la Canebiere, Marseille'),
('Leroy', 'Marie', 'leroy@email.com', '+32 477 45 67 89', 'Promenade des Anglais, Nice'),
('Ken', 'bray', 'ken@email.com', '+32 478 56 78 90', 'Rue de la Dalbade, Toulouse');

-- Comptes
INSERT INTO COMPTE (LOGIN, PASSWORD, IDPROPRIETAIRE, ROLE) VALUES
('admin', '<BCRYPT_HASH_ADMIN>', 1, 'ADMIN'),
('chef', '<BCRYPT_HASH_CHEF>', 2, 'CUISINIER'),
('serveur', '<BCRYPT_HASH_SERVEUR>', 3, 'SERVEUR'),
('caissier', '<BCRYPT_HASH_CAISSIER>', 4, 'CAISSIER'),
('feuba', '<BCRYPT_HASH_1>', 1, 'CLIENT'),
('joel', '<BCRYPT_HASH_2>', 2, 'CLIENT'),
('durand', '<BCRYPT_HASH_3>', 3, 'CLIENT'),
('leroy', '<BCRYPT_HASH_4>', 4, 'CLIENT'),
('ken', '<BCRYPT_HASH_5>', 5, 'CLIENT');


-- Reservations
INSERT INTO HORAIRE_RESERVATION (IDTABLE, IDCLIENT, STATUT, ECHEANCE, NOMBRE_PERSONNE, DATE_DEBUT, DATE_FIN) VALUES
(10, 1, 'TERNINEE', '2025-04-10 22:22:00', 2, '2025-04-10 20:00:00', '2025-04-10 22:00:00'),
(15, 2, 'ACTIVE', '2025-04-15 19:00:00', 4, '2025-04-15 17:00:00', '2025-04-15 19:00:00'),
(18, 3, 'ANNULEE', '2025-04-15 20:30:00', 2, '2025-04-15 18:30:00', '2025-04-15 20:30:00'),
(7, 4, 'ACTIVE', '2025-04-15 21:00:00', 3, '2025-04-15 19:00:00', '2025-04-15 21:00:00'),
(19, 5, 'ACTIVE', '2025-04-15 22:30:00', 5, '2025-04-15 20:30:00', '2025-04-15 22:30:00'),
(20, 5, 'ACTIVE', '2025-04-16 22:30:00', 2, '2025-04-16 20:30:00', '2025-04-16 22:30:00'),
(3, 2, 'ACTIVE', '2025-04-05 22:30:00', 2, '2025-04-05 20:30:00', '2025-04-05 22:30:00');

-- Commandes
INSERT INTO COMMANDE (IDTABLE, IDCLIENT, STATUT) VALUES 
(1, 1, 'Confirmee'),
(9, 4, 'Terminee'),
(12, 3, 'Annulee'),
(6, 2, 'Confirmee'),
(4, 4, 'Confirmee'),
(8, 5, 'Confirmee'),
(10, 5, 'Terminee'),
(19, 5, 'Confirmee');

INSERT INTO PLATS (NOM, QUANTITE, PRIX) VALUES 
('Taro', 50, 17.50),
('Koki', 30, 15.30),
('Salade Cesar', 20, 7.00),
('Burger', 40, 11.00),
('Sushi', 25, 14.00);

INSERT INTO COMPOSER (IDPLATS, IDCOMMANDE, NBPLATS) VALUES 
(1, 1, 2),
(2, 2, 1),
(3, 3, 3),
(4, 4, 1),
(5, 5, 2);

INSERT INTO BOISSONS (NOM, QUANTITE, PRIX) VALUES 
('Coca-Cola', 100, 13.00),
('Sprite', 150, 12.40),
('Eau Minerale', 200, 6.00),
('Jus d''Orange', 120, 9.00),
('The Glace', 80, 12.70);

INSERT INTO CONTENIR (IDBOISSONS, IDCOMMANDE, NBBOISSONS) VALUES 
(1, 1, 3),
(2, 2, 2),
(3, 3, 4),
(4, 4, 1),
(5, 5, 2);

-- Création des tickets pour les commandes existantes
INSERT INTO TICKET (IDCOMMANDE, PRIX) 
SELECT IDCOMMANDE, 0.00 FROM COMMANDE;
