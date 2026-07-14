SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- =========================================
-- DATABASE
-- =========================================

CREATE DATABASE IF NOT EXISTS terra_systcm;
USE terra_systcm;

-- =========================================
-- USERS
-- =========================================

CREATE TABLE users(
    users_id INT AUTO_INCREMENT PRIMARY KEY,

    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,

    date_of_birth DATE,

    nationality ENUM(
        'CAMEROONIAN',
        'ETHIOPIAN',
        'NIGERIAN',
        'MALIAN',
        'TCHADIAN',
        'GUINEAN',
        'SENEGALESE',
        'CONGOLESE'
    ),

    profession VARCHAR(100),

    email VARCHAR(100) UNIQUE NOT NULL,

    phone_number VARCHAR(20),

    u_password VARCHAR(255) NOT NULL,

    id_card_number VARCHAR(55) UNIQUE,

    u_role ENUM(
        'citizen',
        'DO_officer',
        'land_officer',
        'committee',
        'geometre',
        'conservation',
        'admin',
        'delege',
        'notary'
    ) DEFAULT 'citizen',

    created_on_the TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;

-- =========================================
-- LAND APPLICATIONS
-- =========================================

CREATE TABLE land_applications(

    land_applications_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    a_type ENUM(
        'direct',
        'concession',
        'verification',
        'transfer',
        'division'
    ) NOT NULL,

    a_status ENUM(
        'PENDING',
        'APPROVED',
        'DECLINED'
    ) DEFAULT 'PENDING',

    current_stage VARCHAR(100) DEFAULT 'Submitted',

    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

CREATE TABLE application_reviews(

    review_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT NOT NULL,

    reviewed_by INT NOT NULL,

    reviewer_role VARCHAR(100),

    review_action ENUM(
        'APPROVED',
        'REJECTED',
        'PENDING'
    ),

    review_comment TEXT,

    reviewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE,

    FOREIGN KEY (reviewed_by)
    REFERENCES users(users_id)
    ON DELETE CASCADE
);

-- =========================================
-- LAND PARCELS
-- =========================================

CREATE TABLE land_parcels(

    land_parcels_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT NOT NULL,

    p_location TEXT,

    surface_area DECIMAL(10,2),

    gps_coordinates VARCHAR(255),

    boundary VARCHAR(255),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- LAND OWNERSHIPS
-- =========================================

CREATE TABLE land_ownerships(

    land_ownerships_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    land_parcels_id INT NOT NULL,

    marital_status BOOLEAN DEFAULT FALSE,

    l_start_date DATE,

    end_date DATE NULL,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_parcels_id)
    REFERENCES land_parcels(land_parcels_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- DOCUMENTS
-- =========================================

CREATE TABLE documents(

    documents_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT NOT NULL,

    document_type VARCHAR(150),

    file_path VARCHAR(300),

    application_status ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',

    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- WORKFLOW STAGES
-- =========================================

CREATE TABLE workflow_stages(

    workflow_stages_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT NOT NULL,

    actor_role ENUM(
        'citizen',
        'DO_officer',
        'land_officer',
        'committee',
        'geometre',
        'conservation',
        'admin',
        'delege',
        'notary'
    ),

    application_status VARCHAR(50),

    received_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    completed_date TIMESTAMP NULL,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- PAYMENTS
-- =========================================

CREATE TABLE payments(

    payments_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    land_applications_id INT NOT NULL,

    amount DECIMAL(10,2),

    method ENUM(
        'MTN_MOMO',
        'ORANGE_MONEY'
    ),

    application_status ENUM(
        'pending',
        'success',
        'failed'
    ) DEFAULT 'pending',

    transaction_reference VARCHAR(255),

    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- NOTIFICATIONS
-- =========================================

CREATE TABLE notifications(

    notifications_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    n_message TEXT,

    n_status ENUM(
        'unread',
        'read'
    ) DEFAULT 'unread',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- LAND IMMATRICULATIONS
-- =========================================

CREATE TABLE land_immatriculations(

    immatriculation_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT NOT NULL,

    immatriculation_type ENUM(
        'direct',
        'concession'
    ),

    mise_en_valeur BOOLEAN DEFAULT FALSE,

    mise_en_valeur_date DATE NULL,

    i_status ENUM(
        'pending',
        'under_review',
        'approved',
        'rejected'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- DIRECT IMMATRICULATION
-- =========================================

CREATE TABLE direct_immatriculation(

    d_imm_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    immatriculation_id INT NOT NULL,

    land_applications_id INT NOT NULL,

    first_name VARCHAR(100) NOT NULL,

    last_name VARCHAR(100) NOT NULL,

    filliation VARCHAR(150),

    u_address VARCHAR(255) NOT NULL,

    id_card_number VARCHAR(100) NOT NULL,

    id_card_front VARCHAR(255) NOT NULL,

    id_card_back VARCHAR(255) NOT NULL,

    nationality VARCHAR(100) NOT NULL,

    profession VARCHAR(100) NOT NULL,

    marital_status BOOLEAN NOT NULL,

    imm_condition BOOLEAN NOT NULL,

    land_size FLOAT NOT NULL,

    land_location VARCHAR(255) NOT NULL,

    additional_descr VARCHAR(500),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (immatriculation_id)
    REFERENCES land_immatriculations(immatriculation_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- CONCESSION
-- =========================================

CREATE TABLE concession(

    concession_id INT AUTO_INCREMENT PRIMARY KEY,

    users_id INT NOT NULL,

    immatriculation_id INT NOT NULL,

    land_applications_id INT NOT NULL,

    concession_start_date DATE NOT NULL,

    concession_title VARCHAR(255) NOT NULL,

    requested_size FLOAT NOT NULL,

    concession_duration INT NOT NULL,

    c_location VARCHAR(255) NOT NULL,

    purpose_of_concession TEXT NOT NULL,

    business_plan VARCHAR(255) NOT NULL,

    identification_document VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (users_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (immatriculation_id)
    REFERENCES land_immatriculations(immatriculation_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE

) ENGINE=InnoDB;

-- =========================================
-- LAND VERIFICATIONS
-- =========================================

CREATE TABLE land_verifications(

    verification_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT,

    land_parcels_id INT,

    verification_type ENUM(
        'UTM',
        'GAUSS',
        'GPS'
    ),

    result ENUM(
        'valid',
        'invalid',
        'pending'
    ) DEFAULT 'pending',

    verified_by INT,

    verification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_parcels_id)
    REFERENCES land_parcels(land_parcels_id)
    ON DELETE CASCADE,

    FOREIGN KEY (verified_by)
    REFERENCES users(users_id)
    ON DELETE SET NULL

) ENGINE=InnoDB;

-- =========================================
-- LAND TRANSFERS
-- =========================================

CREATE TABLE land_transfers(

    transfer_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT,

    land_parcels_id INT,

    land_certificate_id VARCHAR(100),

    current_owner_id_cards_number VARCHAR(100),

    from_owner_id INT,

    to_owner_id INT,

    transfer_type ENUM(
        'sale',
        'inheritance',
        'mutation'
    ),

    notary_id INT,

    transfer_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_parcels_id)
    REFERENCES land_parcels(land_parcels_id)
    ON DELETE CASCADE,

    FOREIGN KEY (from_owner_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (to_owner_id)
    REFERENCES users(users_id)
    ON DELETE CASCADE,

    FOREIGN KEY (notary_id)
    REFERENCES users(users_id)
    ON DELETE SET NULL

) ENGINE=InnoDB;

-- =========================================
-- LAND DIVISIONS
-- =========================================

CREATE TABLE land_divisions(

    division_id INT AUTO_INCREMENT PRIMARY KEY,

    land_applications_id INT,

    land_parcels_id INT,

    division_type ENUM(
        'lotissement',
        'morcellement'
    ),

    new_parcels_count INT,

    approved_by INT,

    division_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (land_applications_id)
    REFERENCES land_applications(land_applications_id)
    ON DELETE CASCADE,

    FOREIGN KEY (land_parcels_id)
    REFERENCES land_parcels(land_parcels_id)
    ON DELETE CASCADE,

    FOREIGN KEY (approved_by)
    REFERENCES users(users_id)
    ON DELETE SET NULL

) ENGINE=InnoDB;

-- =========================================
-- SAMPLE USERS
-- =========================================

-- NOTE: All demo accounts below use the password:  Passer123!
-- The hash is a real bcrypt hash generated with PHP's password_hash(),
-- so it works correctly with the password_verify() check in login.php.
-- (The previous sample hash '$2y$10$abcdefghijklmnopqrstuv' was not a
-- valid bcrypt hash at all, so no demo account could ever log in.)

INSERT INTO users(
    first_name,
    last_name,
    date_of_birth,
    nationality,
    profession,
    email,
    phone_number,
    u_password,
    id_card_number,
    u_role
)
VALUES
(
    'TAMO DEFFO',
    'ESDRAS',
    '2004-05-20',
    'CAMEROONIAN',
    'Teacher',
    'falconyp@gmail.com',
    '682244556',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'A1914677884',
    'citizen'
),

(
    'Marie',
    'Nguema',
    '1988-10-12',
    'CAMEROONIAN',
    'DO Officer',
    'do@terra.com',
    '677000111',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'DO998877',
    'DO_officer'
),

(
    'Paul',
    'Etoundi',
    '1985-03-14',
    'CAMEROONIAN',
    'Committee Inspector',
    'committee@terra.com',
    '677000112',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'COM998877',
    'committee'
),

(
    'Alice',
    'Mballa',
    '1982-07-22',
    'CAMEROONIAN',
    'Land Affairs Officer',
    'landaffairs@terra.com',
    '677000113',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'LO998877',
    'land_officer'
),

(
    'Jean',
    'Fotso',
    '1980-11-05',
    'CAMEROONIAN',
    'Geometre',
    'geometre@terra.com',
    '677000114',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'GEO998877',
    'geometre'
),

(
    'Chantal',
    'Ngo Bakoa',
    '1979-09-30',
    'CAMEROONIAN',
    'Conservation Officer',
    'conservation@terra.com',
    '677000115',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'CON998877',
    'conservation'
),

(
    'Samuel',
    'Biya Ondoa',
    '1975-01-18',
    'CAMEROONIAN',
    'System Administrator',
    'admin@terra.com',
    '677000116',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'ADM998877',
    'admin'
),

(
    'Brigitte',
    'Onana',
    '1983-04-09',
    'CAMEROONIAN',
    'Delegate',
    'delege@terra.com',
    '677000117',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'DEL998877',
    'delege'
),

(
    'Robert',
    'Ndifor',
    '1978-06-25',
    'CAMEROONIAN',
    'Notary',
    'notary@terra.com',
    '677000118',
    '$2b$10$tnRwSaF3/7ygo6sDgNEfF./Z7yBGwUvO7w9bb75Eb7tIyHokqYkwW',
    'NOT998877',
    'notary'
);