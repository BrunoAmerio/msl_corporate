<?php

/**
 * Servicio para el manejo de envío de emails
 * Maneja tanto formularios de contacto como de empleo
 */
class EmailService {
    
    /**
     * Envía email del formulario de contacto
     */
    public function sendContactEmail($data) {
        try {
            // Validar datos requeridos
            $requiredFields = ['name', 'company', 'email', 'telephone', 'country', 'message', 'current_language'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => "Campo requerido faltante: $field"
                    ];
                }
            }

            // Obtener asunto según idioma
            $subject = $this->getContactSubject($data['current_language']);
            
            // Crear cuerpo del email
            $body = $this->createContactEmailBody($data);
            
            // Enviar email
            $emailSent = $this->sendEmail(
                'marketing@mslcorporate.com', 
                $subject, 
                $body, 
                $data['email'], 
                $data['name']
            );

            if ($emailSent) {
                return [
                    'success' => true,
                    'message' => 'Email enviado exitosamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error al enviar el email'
                ];
            }

        } catch (Exception $e) {
            error_log("Error en sendContactEmail: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del servidor'
            ];
        }
    }

    /**
     * Envía email del formulario de empleo
     */
    public function sendJobEmail($data) {
        try {
            // Validar datos requeridos
            $requiredFields = ['fullname', 'message', 'current_language'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => "Campo requerido faltante: $field"
                    ];
                }
            }

            // Validar archivo CV
            if (!isset($_FILES['cv-file']) || $_FILES['cv-file']['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'message' => 'CV requerido'
                ];
            }

            // Validar tamaño del archivo (5MB máximo)
            if ($_FILES['cv-file']['size'] > 5 * 1024 * 1024) {
                return [
                    'success' => false,
                    'message' => 'El archivo CV excede el tamaño máximo de 5MB'
                ];
            }

            // Obtener asunto según idioma
            $subject = $this->getJobSubject($data['current_language']);
            
            // Crear cuerpo del email
            $body = $this->createJobEmailBody($data);
            
            // Enviar email con adjunto
            $emailSent = $this->sendEmailWithAttachment(
                'busquedas@mslcorporate.com', 
                $subject, 
                $body, 
                'noreply@mslcorporate.com',
                $data['fullname'],
                $_FILES['cv-file']
            );

            if ($emailSent) {
                return [
                    'success' => true,
                    'message' => 'Solicitud de empleo enviada exitosamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error al enviar la solicitud de empleo'
                ];
            }

        } catch (Exception $e) {
            error_log("Error en sendJobEmail: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del servidor'
            ];
        }
    }

    /**
     * Obtiene el asunto del email de contacto según el idioma
     */
    private function getContactSubject($languageCode) {
        $subjects = [
            'es' => 'Consulta de contacto desde la página web',
            'en' => 'Contact inquiry from website',
            'pt' => 'Consulta de contato do site'
        ];

        return $subjects[$languageCode] ?? $subjects['es'];
    }

    /**
     * Obtiene el asunto del email de empleo según el idioma
     */
    private function getJobSubject($languageCode) {
        $subjects = [
            'es' => 'Formulario de empleo',
            'en' => 'Job application form',
            'pt' => 'Formulário de emprego'
        ];

        return $subjects[$languageCode] ?? $subjects['es'];
    }

    /**
     * Crea el cuerpo del email de contacto
     */
    private function createContactEmailBody($data) {
        $name = htmlspecialchars($data['name']);
        $email = htmlspecialchars($data['email']);
        $telephone = htmlspecialchars($data['telephone']);
        $company = htmlspecialchars($data['company']);
        $country = htmlspecialchars($data['country']);
        $message = htmlspecialchars($data['message']);

        switch ($data['current_language']) {
            case 'en':
                return "
New contact inquiry from website

Name: $name
Email: $email
Phone: $telephone
Company: $company
Country: $country

Message:
$message

---
This message was sent from the MSL LATAM website contact form.
                ";

            case 'pt':
                return "
Nova consulta de contato do site

Nome: $name
Email: $email
Telefone: $telephone
Empresa: $company
País: $country

Mensagem:
$message

---
Esta mensagem foi enviada do formulário de contato do site MSL LATAM.
                ";

            default: // español
                return "
Nueva consulta de contacto desde la página web

Nombre: $name
Email: $email
Teléfono: $telephone
Empresa: $company
País: $country

Mensaje:
$message

---
Este mensaje fue enviado desde el formulario de contacto del sitio web MSL LATAM.
                ";
        }
    }

    /**
     * Crea el cuerpo del email de empleo
     */
    private function createJobEmailBody($data) {
        $fullName = htmlspecialchars($data['fullname']);
        $message = htmlspecialchars($data['message']);
        $fileName = htmlspecialchars($_FILES['cv-file']['name']);

        switch ($data['current_language']) {
            case 'en':
                return "
New job application

Name: $fullName

Message:
$message

CV file attached: $fileName

---
This application was submitted from the MSL LATAM website job form.
                ";

            case 'pt':
                return "
Nova candidatura de emprego

Nome: $fullName

Mensagem:
$message

Arquivo CV anexado: $fileName

---
Esta candidatura foi submetida do formulário de emprego do site MSL LATAM.
                ";

            default: // español
                return "
Nueva solicitud de empleo

Nombre: $fullName

Mensaje:
$message

Archivo CV adjunto: $fileName

---
Esta solicitud fue enviada desde el formulario de empleo del sitio web MSL LATAM.
                ";
        }
    }

    /**
     * Envía un email básico
     */
    private function sendEmail($to, $subject, $body, $fromEmail = null, $fromName = null) {
        try {
            // Configurar headers
            $headers = [];
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/plain; charset=UTF-8';
            
            if ($fromEmail && $fromName) {
                $headers[] = "From: $fromName <$fromEmail>";
                $headers[] = "Reply-To: $fromEmail";
            } else {
                $headers[] = "From: MSL LATAM Website <noreply@mslcorporate.com>";
            }
            
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            // Enviar email
            return mail($to, $subject, $body, implode("\r\n", $headers));

        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía un email con archivo adjunto
     */
    private function sendEmailWithAttachment($to, $subject, $body, $fromEmail, $fromName, $attachment) {
        try {
            // Leer el archivo adjunto
            $fileData = file_get_contents($attachment['tmp_name']);
            $fileName = $attachment['name'];
            $fileType = $attachment['type'];
            
            // Crear boundary único
            $boundary = md5(time());
            
            // Headers del email
            $headers = [];
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = "From: $fromName <$fromEmail>";
            $headers[] = "Reply-To: $fromEmail";
            $headers[] = "Content-Type: multipart/mixed; boundary=\"$boundary\"";
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            // Construir el cuerpo del mensaje
            $message = "--$boundary\r\n";
            $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message .= $body . "\r\n\r\n";
            
            // Adjuntar archivo
            $message .= "--$boundary\r\n";
            $message .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n";
            $message .= chunk_split(base64_encode($fileData)) . "\r\n";
            $message .= "--$boundary--\r\n";

            // Enviar email
            return mail($to, $subject, $message, implode("\r\n", $headers));

        } catch (Exception $e) {
            error_log("Error enviando email con adjunto: " . $e->getMessage());
            return false;
        }
    }
}

