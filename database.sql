CREATE DATABASE IF NOT EXISTS medicare_plus;
USE medicare_plus;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('patient','doctor','admin') NOT NULL DEFAULT 'patient',
    phone VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(20) DEFAULT '✚',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(255),
    experience INT DEFAULT 0,
    location VARCHAR(100),
    consultation_fee DECIMAL(10,2) DEFAULT 0,
    availability VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0,
    bio TEXT,
    service_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255),
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    UNIQUE KEY unique_doctor_slot (doctor_id, appointment_date, appointment_time)
);

CREATE TABLE medical_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT,
    title VARCHAR(200) NOT NULL,
    report_text TEXT,
    report_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

INSERT INTO services (name, description, icon) VALUES
('Cardiology','Diagnosis and treatment of heart and cardiovascular conditions.','♥'),
('Pediatrics','Healthcare services focused on infants, children and adolescents.','✚'),
('Radiology','Medical imaging and diagnostic imaging services.','◉'),
('General Medicine','General medical consultation and health assessment.','✚'),
('Dermatology','Diagnosis and treatment of skin, hair and nail conditions.','●'),
('Emergency Care','Urgent medical support for emergencies and critical conditions.','!');

INSERT INTO users (full_name,email,password,role,phone)
VALUES
('Dr. Amaya Perera','doctor@medicare.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaN9gXn0Qz0Q7f4G6VhM3Q1G1e',
'doctor','0771234567'),
('System Administrator','admin@medicare.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEaN9gXn0Qz0Q7f4G6VhM3Q1G1e',
'admin','0771111111');

INSERT INTO doctors
(user_id,specialization,qualification,experience,location,consultation_fee,availability,rating,bio,service_id)
VALUES
(1,'Cardiology','MBBS, MD Cardiology',12,'Colombo',5000,
'Monday - Friday, 9:00 AM - 3:00 PM',4.80,
'Experienced cardiology specialist providing patient-centred cardiovascular care.',1);