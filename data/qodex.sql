use qodexV2 ;

--@block 
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY , 
    nom VARCHAR(255) NOT NULL, 
    email VARCHAR(255) NOT NULL UNIQUE, 
    password_hash VARCHAR(255) NOT NULL, 
    role ENUM('enseignant' ,'etudiant ' ) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME 
    
);

--@block 
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL ,
    description VARCHAR(400) ,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    FOREIGN KEY (created_by) REFERENCES user(id)

);

--@block 
CREATE TABLE quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description VARCHAR(400),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN NOT NULL DEFAULT 1,
    enseignant_id INT NOT NULL,
    categorie_id INT NOT NULL,
    FOREIGN KEY (enseignant_id) REFERENCES user(id),
    FOREIGN KEY (categorie_id) REFERENCES category(id)
);

--@block 

CREATE TABLE question (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question VARCHAR(500) NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    option4 VARCHAR(255) NOT NULL,
    correct_option INT NOT NULL CHECK (correct_option BETWEEN 1 AND 4),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE
);


--@block 

CREATE TABLE result (
    id INT AUTO_INCREMENT PRIMARY KEY,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    etudiant_id INT NOT NULL,
    quiz_id INT NOT NULL,
    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES user(id),
    FOREIGN KEY (quiz_id) REFERENCES quiz(id)
);


--@block 

INSERT INTO user (nom, email, password_hash, role, created_at)
VALUES
('Alice Dupont', 'alice@school.com', SHA2('code1', 256), 'enseignant', NOW()),
('Bernard Leroy', 'bernard@school.com', SHA2('code2', 256), 'enseignant', NOW()),
('Claire Martin', 'claire@school.com', SHA2('code3', 256), 'enseignant', NOW()),
('David Étudiant', 'david@student.com',  SHA2('code3', 256) , 'etudiant', NOW()),
('Emma Étudiante', 'emma@student.com',   SHA2('code3', 256), 'etudiant', NOW()),
('Farid Étudiant', 'farid@student.com',  SHA2('code3', 256), 'etudiant', NOW()),
('Gina Étudiante', 'gina@student.com',  SHA2('code3', 256), 'etudiant', NOW()),
('Hugo Étudiant', 'hugo@student.com',  SHA2('code3', 256), 'etudiant', NOW()),
('Ines Étudiante', 'ines@student.com',  SHA2('code3', 256), 'etudiant', NOW()),
('Jack Étudiant', 'jack@student.com',  SHA2('code3', 256), 'etudiant', NOW());




--@block 
INSERT INTO category (nom, description, created_by)
VALUES
('Mathématiques', 'Cours et exercices', 1),
('Sciences', 'Physique, chimie, biologie', 1),
('Histoire', 'Histoire ancienne et moderne', 2),
('Informatique', 'Programmation et algorithmique', 3),
('Géographie', 'Cartes et territoires', 2),
('Langues', 'Anglais, Français, Espagnol', 3);


--@block 
INSERT INTO quiz (titre, description, is_active, enseignant_id, categorie_id)
VALUES
('Algèbre de base', 'Quiz sur les équations simples', 1, 1, 1),
('Géométrie', 'Angles et triangles', 1, 1, 1),
('Physique – Forces', 'Notions fondamentales', 1, 1, 2),
('Chimie – Atomes', 'Structure atomique', 1, 2, 2),
('Histoire Romaine', 'Empire romain', 1, 2, 3),
('Révolution Française', '1789-1799', 1, 2, 3),
('Python – Syntaxe', 'Bases de Python', 1, 3, 4),
('Algorithmique', 'Logique et structures', 1, 3, 4),
('Capitale du Monde', 'Quiz de géographie', 1, 1, 5),
('Vocabulaire Anglais', 'Mots essentiels', 1, 3, 6);


--@block 
INSERT INTO question (quiz_id, question, option1, option2, option3, option4, correct_option)
VALUES
-- Quiz 1 (Algèbre de base)
(1,'2+2 = ?', '3','4','5','6', 2),
(1,'5 - 3 = ?', '1','2','3','4', 2),
(1,'3×3 = ?', '6','7','8','9', 4),
(1,'8/2 = ?', '2','3','4','5', 3),

-- Quiz 2 (Géométrie)
(2,'Un triangle a combien de côtés ?', '2','3','4','5', 2),
(2,'Un angle droit mesure ?', '45°','90°','180°','360°', 2),
(2,'Somme des angles d’un triangle ?', '90°','180°','270°','360°', 2),
(2,'Un carré a ?', '3 côtés','4 côtés','5 côtés','6 côtés', 2),

-- Quiz 3 (Physique)
(3,'Unité de force ?', 'Newton','Watt','Volt','Joule', 1),
(3,'La gravité est ?', 'Une force','Une énergie','Un gaz','Une onde', 1),
(3,'Force = masse × ?', 'Distance','Temps','Vitesse','Accélération', 4),
(3,'Symbole de la force ?', 'F','G','P','V', 1),

-- Quiz 4 (Chimie – Atomes)
(4,'Électrons ont une charge ?', 'Positive','Négative','Neutre','Variable', 2),
(4,'Atome composé de ?', 'Protons','Neutrons','Électrons','All of them', 4),
(4,'Symbole de l’oxygène ?', 'O','H','C','N', 1),
(4,'Structure atomique modèle ?', 'Bohr','Einstein','Newton','Faraday', 1),

-- Quiz 5 (Histoire Romaine)
(5,'Fondateur légendaire de Rome ?', 'Romulus','César','Pompée','Augustus', 1),
(5,'César assassiné en ?', '44 av JC','30 av JC','14 ap JC','100 ap JC', 1),
(5,'Langue de Rome ?', 'Latin','Grec','Arabe','Français', 1),
(5,'Empire romain tombe en ?', '476','800','1000','1200', 1),

-- Quiz 6 (Révolution française)
(6,'Début en ?', '1789','1792','1804','1815', 1),
(6,'Prise de la Bastille ?', '14 juil 1789','10 août 1792','9 thermidor','12 vendémiaire', 1),
(6,'Roi exécuté ?', 'Louis XIV','Louis XVI','Napoléon','Charles X', 2),
(6,'Marianne symbolise ?', 'Royauté','République','Empire','Église', 2),

-- Quiz 7 (Python Syntaxe)
(7,'Extension Python ?', '.py','.js','.php','.java', 1),
(7,'Print correct ?', 'echo()','printf()','print()','say()', 3),
(7,'Type liste ?', '[]','{}','()','<>', 1),
(7,'Commentaire ?', '//','#','/* */','--', 2),

-- Quiz 8 (Algorithmique)
(8,'Un algorithme est ?', 'Recette','Programme','Ordre','Boucle', 1),
(8,'Une boucle est ?', 'Répétition','Condition','Fonction','Variable', 1),
(8,'Un test est ?', 'Boucle','Condition','Array','Classe', 2),
(8,'Variable stocke ?', 'Texte','Nombre','Valeurs','Données', 4),

-- Quiz 9 (Capitale du Monde)
(9,'Capitale de France ?', 'Paris','Rome','Madrid','Berlin', 1),
(9,'Capitale du Japon ?', 'Tokyo','Osaka','Kyoto','Nagoya', 1),
(9,'Capitale de l’Espagne ?', 'Madrid','Barcelona','Sevilla','Valencia', 1),
(9,'Capitale du Canada ?', 'Toronto','Ottawa','Vancouver','Montréal', 2),

-- Quiz 10 (Vocabulaire Anglais)
(10,'Dog = ?', 'Chat','Chien','Cheval','Poisson', 2),
(10,'House = ?', 'Maison','Voiture','Arbre','Route', 1),
(10,'Run = ?', 'Courir','Manger','Dormir','Lire', 1),
(10,'Blue = ?', 'Bleu','Rouge','Vert','Noir', 1);


--@block 
INSERT INTO result (score, total_questions, etudiant_id, quiz_id)
VALUES
(3,4,4,1),(4,4,5,1),(2,4,6,1),
(4,4,7,2),(3,4,8,2),
(2,4,9,3),(4,4,10,3),
(3,4,4,4),(1,4,5,4),
(4,4,6,5),(2,4,7,5),
(3,4,8,6),(4,4,9,6),
(3,4,10,7),(4,4,4,7),
(2,4,5,8),(3,4,6,8),
(4,4,7,9),(3,4,8,9),
(4,4,9,10);



