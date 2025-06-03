<?php
namespace app\presentation\controllers;

use app\infrastructure\database\DatabaseConnect;
use TCPDF; // Asegúrate de que Composer cargue la clase TCPDF
use PDO;
use DateTime;
use DateInterval;

class ReportController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance();
    }

    // =========================== USUARIOS ===========================

    /**
     * 1. Listado completo de usuarios (activos vs archivados) → CSV
     */
    public function exportUsuariosListadoCsv(): void
    {
        // 1) Preparar cabeceras CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=usuarios_listado.csv');

        $output = fopen('php://output', 'w');
        // Encabezados de columna:
        fputcsv($output, [
            'ID', 'Nombre', 'Correo', 'Rol',
            'Fecha Registro', 'Último Login', 'Estado'
        ]);

        // 2) Traer todos (activos y archivados)
        $stmt = $this->db->query("
            SELECT id_user, nombre, correo, rol, created_at, last_login, archived 
            FROM usuario 
            ORDER BY id_user ASC
        ");
        while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $u['id_user'],
                $u['nombre'],
                $u['correo'],
                $u['rol'],
                date('d/m/Y H:i', strtotime($u['created_at'])),
                $u['last_login']
                    ? date('d/m/Y H:i', strtotime($u['last_login']))
                    : '-',
                $u['archived'] ? 'Archivado' : 'Activo'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 1. Listado completo de usuarios → PDF (TCPDF)
     */
    public function exportUsuariosListadoPdf(): void
    {
        // 1) Obtener datos
        $stmt = $this->db->query("
            SELECT id_user, nombre, correo, rol, created_at, last_login, archived 
            FROM usuario 
            ORDER BY id_user ASC
        ");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2) Instanciar TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Listado de Usuarios');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // 3) Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Listado Completo de Usuarios', 0, 1, 'C');
        $pdf->Ln(4);

        // 4) Tabla
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(12, 8, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(45, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Correo', 1, 0, 'C', 1);
        $pdf->Cell(20, 8, 'Rol', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Fecha Reg.', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Último Login', 1, 0, 'C', 1);
        $pdf->Cell(15, 8, 'Estado', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($usuarios as $u) {
            $pdf->Cell(12, 7, $u['id_user'], 1, 0, 'C');
            $pdf->Cell(45, 7, $u['nombre'], 1, 0);
            $pdf->Cell(50, 7, $u['correo'], 1, 0);
            $pdf->Cell(20, 7, $u['rol'], 1, 0, 'C');
            $pdf->Cell(30, 7, date('d/m/Y', strtotime($u['created_at'])), 1, 0, 'C');
            $pdf->Cell(
                30,
                7,
                $u['last_login']
                    ? date('d/m/Y', strtotime($u['last_login']))
                    : '-',
                1,
                0,
                'C'
            );
            $pdf->Cell(15, 7, $u['archived'] ? 'X' : '', 1, 1, 'C');
        }

        // 5) Enviar al navegador
        $pdf->Output('usuarios_listado.pdf', 'I');
        exit;
    }

    /**
     * 2. Nuevos registros de usuario por fecha → CSV
     */
    public function exportUsuariosNuevosCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=usuarios_nuevos_por_fecha.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Fecha', 'Cantidad de registros']);

        $stmt = $this->db->query("
            SELECT 
              DATE(created_at) AS fecha, 
              COUNT(*) AS total 
            FROM usuario 
            GROUP BY DATE(created_at) 
            ORDER BY fecha ASC
        ");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                date('d/m/Y', strtotime($row['fecha'])),
                $row['total']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 2. Nuevos registros de usuario por fecha → PDF
     */
    public function exportUsuariosNuevosPdf(): void
    {
        $stmt = $this->db->query("
            SELECT 
              DATE(created_at) AS fecha, 
              COUNT(*) AS total 
            FROM usuario 
            GROUP BY DATE(created_at) 
            ORDER BY fecha ASC
        ");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Usuarios Nuevos por Fecha');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Usuarios Nuevos por Fecha', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(50, 8, 'Fecha', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Cantidad', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($datos as $d) {
            $pdf->Cell(50, 7, date('d/m/Y', strtotime($d['fecha'])), 1, 0, 'C');
            $pdf->Cell(50, 7, $d['total'], 1, 1, 'C');
        }

        $pdf->Output('usuarios_nuevos_por_fecha.pdf', 'I');
        exit;
    }

    /**
     * 3. Historial de logins de usuarios → CSV
     *    (Este ejemplo asume que tienes una tabla `user_logins(id, id_user, login_at)`)
     */
    public function exportUsuariosHistorialLoginsCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=usuarios_historial_logins.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Login', 'ID Usuario', 'Nombre Usuario', 'Correo', 'Fecha y Hora de Login']);

        $stmt = $this->db->query("
            SELECT 
              ul.id AS id_login,
              u.id_user,
              u.nombre,
              u.correo,
              ul.login_at
            FROM user_logins AS ul
            JOIN usuario AS u ON ul.id_user = u.id_user
            ORDER BY ul.login_at DESC
        ");

        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $r['id_login'],
                $r['id_user'],
                $r['nombre'],
                $r['correo'],
                date('d/m/Y H:i', strtotime($r['login_at']))
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 3. Historial de logins de usuarios → PDF
     */
    public function exportUsuariosHistorialLoginsPdf(): void
    {
        $stmt = $this->db->query("
            SELECT 
              ul.id AS id_login,
              u.id_user,
              u.nombre,
              u.correo,
              ul.login_at
            FROM user_logins AS ul
            JOIN usuario AS u ON ul.id_user = u.id_user
            ORDER BY ul.login_at DESC
        ");
        $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Historial de Logins');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Historial de Logins de Usuarios', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(15, 8, 'ID Lgn', 1, 0, 'C', 1);
        $pdf->Cell(15, 8, 'ID U', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Correo', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Fecha y Hora Login', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 8);
        foreach ($historial as $h) {
            $pdf->Cell(15, 6, $h['id_login'], 1, 0, 'C');
            $pdf->Cell(15, 6, $h['id_user'], 1, 0, 'C');
            $pdf->Cell(50, 6, $h['nombre'], 1, 0);
            $pdf->Cell(60, 6, $h['correo'], 1, 0);
            $pdf->Cell(
                40,
                6,
                date('d/m/Y H:i', strtotime($h['login_at'])),
                1,
                1,
                'C'
            );
        }

        $pdf->Output('usuarios_historial_logins.pdf', 'I');
        exit;
    }

    /**
     * 4. Ranking de usuarios más activos → CSV
     *    (Asumimos que “actividad” se mide por cantidad de registros en `user_logins`)
     */
    public function exportUsuariosMasActivosCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=usuarios_mas_activos.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Usuario', 'Nombre', 'Correo', 'Total de Logins']);

        $stmt = $this->db->query("
            SELECT 
              u.id_user,
              u.nombre,
              u.correo,
              COUNT(ul.id) AS total_logins
            FROM usuario AS u
            LEFT JOIN user_logins AS ul ON u.id_user = ul.id_user
            GROUP BY u.id_user, u.nombre, u.correo
            ORDER BY total_logins DESC
            LIMIT 10
        ");

        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $r['id_user'],
                $r['nombre'],
                $r['correo'],
                $r['total_logins']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 4. Ranking de usuarios más activos → PDF
     */
    public function exportUsuariosMasActivosPdf(): void
    {
        $stmt = $this->db->query("
            SELECT 
              u.id_user,
              u.nombre,
              u.correo,
              COUNT(ul.id) AS total_logins
            FROM usuario AS u
            LEFT JOIN user_logins AS ul ON u.id_user = ul.id_user
            GROUP BY u.id_user, u.nombre, u.correo
            ORDER BY total_logins DESC
            LIMIT 10
        ");
        $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Usuarios Más Activos');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Ranking de Usuarios Más Activos', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(20, 8, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Correo', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Logins Tot.', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($ranking as $u) {
            $pdf->Cell(20, 7, $u['id_user'], 1, 0, 'C');
            $pdf->Cell(50, 7, $u['nombre'], 1, 0);
            $pdf->Cell(60, 7, $u['correo'], 1, 0);
            $pdf->Cell(30, 7, $u['total_logins'], 1, 1, 'C');
        }

        $pdf->Output('usuarios_mas_activos.pdf', 'I');
        exit;
    }

    // =========================== EMPLEADOS ===========================

    /**
     * 1. Listado completo de empleados (datos de nómina) → CSV
     */
    public function exportEmpleadosListadoCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empleados_listado.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'ID', 'Nombre', 'Correo', 'Teléfono', 'Puesto',
            'Salario', 'Fecha Contratación', 'Activo', 'Archivado'
        ]);

        $stmt = $this->db->query("
            SELECT 
              id_empleado, nombre, correo_electronico, telefono, puesto, salario, fecha_contratacion, activo, archived
            FROM empleado
            ORDER BY id_empleado ASC
        ");
        while ($e = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $e['id_empleado'],
                $e['nombre'],
                $e['correo_electronico'],
                $e['telefono'],
                $e['puesto'],
                number_format($e['salario'], 2),
                date('d/m/Y', strtotime($e['fecha_contratacion'])),
                $e['activo'] ? 'Sí' : 'No',
                $e['archived'] ? 'Sí' : 'No'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 1. Listado completo de empleados → PDF
     */
    public function exportEmpleadosListadoPdf(): void
    {
        $stmt = $this->db->query("
            SELECT 
              id_empleado, nombre, correo_electronico, telefono, puesto, salario, fecha_contratacion, activo, archived
            FROM empleado
            ORDER BY id_empleado ASC
        ");
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Listado de Empleados');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Listado Completo de Empleados', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(12, 8, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(45, 8, 'Correo', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Teléfono', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Puesto', 1, 0, 'C', 1);
        $pdf->Cell(25, 8, 'Salario', 1, 0, 'C', 1);
        $pdf->Cell(25, 8, 'Fecha Contr.', 1, 0, 'C', 1);
        $pdf->Cell(15, 8, 'Activo', 1, 0, 'C', 1);
        $pdf->Cell(15, 8, 'Archiv.', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 8);
        foreach ($empleados as $e) {
            $pdf->Cell(12, 6, $e['id_empleado'], 1, 0, 'C');
            $pdf->Cell(40, 6, $e['nombre'], 1, 0);
            $pdf->Cell(45, 6, $e['correo_electronico'], 1, 0);
            $pdf->Cell(30, 6, $e['telefono'], 1, 0);
            $pdf->Cell(30, 6, $e['puesto'], 1, 0);
            $pdf->Cell(25, 6, number_format($e['salario'], 2), 1, 0, 'R');
            $pdf->Cell(25, 6, date('d/m/Y', strtotime($e['fecha_contratacion'])), 1, 0, 'C');
            $pdf->Cell(15, 6, $e['activo'] ? 'Sí' : 'No', 1, 0, 'C');
            $pdf->Cell(15, 6, $e['archived'] ? 'Sí' : 'No', 1, 1, 'C');
        }

        $pdf->Output('empleados_listado.pdf', 'I');
        exit;
    }

    /**
     * 2. Reporte de antigüedad / días trabajados → CSV
     */
    public function exportEmpleadosAntiguedadCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empleados_antiguedad.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Fecha Contratación', 'Antigüedad']);

        $stmt = $this->db->query("
            SELECT id_empleado, nombre, fecha_contratacion
            FROM empleado
            ORDER BY fecha_contratacion ASC
        ");

        while ($e = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fC = new DateTime($e['fecha_contratacion']);
            $hoy = new DateTime();
            $diff = $hoy->diff($fC);
            $antiguedad = $diff->y . ' años, ' . $diff->m . ' meses';

            fputcsv($output, [
                $e['id_empleado'],
                $e['nombre'],
                date('d/m/Y', strtotime($e['fecha_contratacion'])),
                $antiguedad
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 2. Reporte de antigüedad / días trabajados → PDF
     */
    public function exportEmpleadosAntiguedadPdf(): void
    {
        $stmt = $this->db->query("
            SELECT id_empleado, nombre, fecha_contratacion
            FROM empleado
            ORDER BY fecha_contratacion ASC
        ");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Antigüedad de Empleados');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Antigüedad de Empleados', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(15, 8, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(60, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Fecha Contrat.', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Antigüedad', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($datos as $e) {
            $fC = new DateTime($e['fecha_contratacion']);
            $hoy = new DateTime();
            $diff = $hoy->diff($fC);
            $antiguedad = $diff->y . ' años, ' . $diff->m . ' meses';

            $pdf->Cell(15, 7, $e['id_empleado'], 1, 0, 'C');
            $pdf->Cell(60, 7, $e['nombre'], 1, 0);
            $pdf->Cell(40, 7, date('d/m/Y', strtotime($e['fecha_contratacion'])), 1, 0, 'C');
            $pdf->Cell(40, 7, $antiguedad, 1, 1, 'C');
        }

        $pdf->Output('empleados_antiguedad.pdf', 'I');
        exit;
    }

    /**
     * 3. Reporte de salarios (total mensual, promedio por puesto, etc.) → CSV
     */
    public function exportEmpleadosSalariosCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empleados_salarios.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Puesto', 'Cantidad Empleados', 'Salario Total', 'Salario Promedio']);

        // Agrupar por puesto: contar empleados activos y calcular sum y avg de salarios
        $stmt = $this->db->query("
            SELECT 
              puesto,
              COUNT(*) AS cantidad,
              SUM(salario) AS salario_total,
              AVG(salario) AS salario_promedio
            FROM empleado
            WHERE activo = TRUE
            GROUP BY puesto
            ORDER BY puesto ASC
        ");

        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $r['puesto'],
                $r['cantidad'],
                number_format($r['salario_total'], 2),
                number_format($r['salario_promedio'], 2)
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * 3. Reporte de salarios → PDF
     */
    public function exportEmpleadosSalariosPdf(): void
    {
        $stmt = $this->db->query("
            SELECT 
              puesto,
              COUNT(*) AS cantidad,
              SUM(salario) AS salario_total,
              AVG(salario) AS salario_promedio
            FROM empleado
            WHERE activo = TRUE
            GROUP BY puesto
            ORDER BY puesto ASC
        ");
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Reporte de Salarios por Puesto');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Salarios por Puesto', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(50, 8, 'Puesto', 1, 0, 'C', 1);
        $pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Salario Total', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Salario Promedio', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($datos as $d) {
            $pdf->Cell(50, 7, $d['puesto'], 1, 0);
            $pdf->Cell(30, 7, $d['cantidad'], 1, 0, 'C');
            $pdf->Cell(40, 7, number_format($d['salario_total'], 2), 1, 0, 'R');
            $pdf->Cell(40, 7, number_format($d['salario_promedio'], 2), 1, 1, 'R');
        }

        $pdf->Output('empleados_salarios.pdf', 'I');
        exit;
    }

    /**
     * 4. Próximos aniversarios laborales → CSV
     *    (Empleados cuyo aniversario cae en los próximos 30 días)
     */
    public function exportEmpleadosAniversariosCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empleados_aniversarios.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Fecha de Contratación', 'Próximo Aniversario', 'Años Cumplidos']);

        // 1) Traer todos los empleados activos (o todos si lo prefieres)
        $stmt = $this->db->query("
            SELECT id_empleado, nombre, fecha_contratacion
            FROM empleado
            WHERE activo = TRUE
        ");
        $hoy = new DateTime();
        $limite = (clone $hoy)->add(new DateInterval('P30D')); // dentro de 30 días

        while ($e = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fC = new DateTime($e['fecha_contratacion']);
            // próximo aniversario en el año actual:
            $anioActual = (int)$hoy->format('Y');
            $proxAniv = DateTime::createFromFormat('Y-m-d', $anioActual . '-' . $fC->format('m-d'));

            // Si ya pasó este año, usar el siguiente año
            if ($proxAniv < $hoy) {
                $proxAniv = (clone $proxAniv)->add(new DateInterval('P1Y'));
            }

            // Si el próximo aniversario cae dentro de los próximos 30 días:
            if ($proxAniv <= $limite) {
                $diff = $proxAniv->diff($fC);
                $anios = $diff->y;
                fputcsv($output, [
                    $e['id_empleado'],
                    $e['nombre'],
                    date('d/m/Y', strtotime($e['fecha_contratacion'])),
                    $proxAniv->format('d/m/Y'),
                    $anios
                ]);
            }
        }

        fclose($output);
        exit;
    }

    /**
     * 4. Próximos aniversarios laborales → PDF
     */
    public function exportEmpleadosAniversariosPdf(): void
    {
        $stmt = $this->db->query("
            SELECT id_empleado, nombre, fecha_contratacion
            FROM empleado
            WHERE activo = TRUE
        ");
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $hoy = new DateTime();
        $limite = (clone $hoy)->add(new DateInterval('P30D'));

        // Filtrar solo los que entran en los próximos 30 días y armar array
        $datos = [];
        foreach ($empleados as $e) {
            $fC = new DateTime($e['fecha_contratacion']);
            $anioActual = (int)$hoy->format('Y');
            $proxAniv = DateTime::createFromFormat('Y-m-d', $anioActual . '-' . $fC->format('m-d'));

            if ($proxAniv < $hoy) {
                $proxAniv = (clone $proxAniv)->add(new DateInterval('P1Y'));
            }

            if ($proxAniv <= $limite) {
                $diff = $proxAniv->diff($fC);
                $anios = $diff->y;
                $datos[] = [
                    'id'           => $e['id_empleado'],
                    'nombre'       => $e['nombre'],
                    'f_contrat'    => $fC->format('d/m/Y'),
                    'prox_aniv'    => $proxAniv->format('d/m/Y'),
                    'anios_cumpl'  => $anios
                ];
            }
        }

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Encomiendas Leandro');
        $pdf->SetAuthor('Administrador');
        $pdf->SetTitle('Próximos Aniversarios Laborales');
        $pdf->SetSubject('Reporte PDF');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Próximos Aniversarios Laborales', 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(15, 8, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(45, 8, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Fecha Contrat.', 1, 0, 'C', 1);
        $pdf->Cell(40, 8, 'Próx. Aniversario', 1, 0, 'C', 1);
        $pdf->Cell(25, 8, 'Años Cumpl.', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($datos as $d) {
            $pdf->Cell(15, 6, $d['id'], 1, 0, 'C');
            $pdf->Cell(45, 6, $d['nombre'], 1, 0);
            $pdf->Cell(40, 6, $d['f_contrat'], 1, 0, 'C');
            $pdf->Cell(40, 6, $d['prox_aniv'], 1, 0, 'C');
            $pdf->Cell(25, 6, $d['anios_cumpl'], 1, 1, 'C');
        }

        $pdf->Output('empleados_aniversarios.pdf', 'I');
        exit;
    }
}
