// Importa las herramientas para hacer consultas SQL en Java
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class ReporteInventario {
    public static void main(String[] args) {
        
        // 1. Host del pooler de São Paulo y puerto 5432
        String url = "jdbc:postgresql://aws-0-sa-east-1.pooler.supabase.com:5432/postgres";
        
        // 2. Tu usuario con el ID de proyecto
        String usuario = "postgres.llafbklyqnvtojblzjyu";
        
        // 3. Tu contraseña de Supabase
        String password = "INVENTARIO-TI";

        System.out.println("==================================================");
        System.out.println("     REPORTE DE INVENTARIO TI (DESDE SUPABASE)    ");
        System.out.println("==================================================");

        try (Connection con = DriverManager.getConnection(url, usuario, password)) {
            System.out.println("[OK] Conexión establecida con éxito desde Java.\n");

            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT id, nombre, tipo, estado FROM equipos ORDER BY id ASC");

            int total = 0;
            while (rs.next()) {
                System.out.printf("#%02d | %-25s | %-12s | [%s]%n",
                    rs.getInt("id"),
                    rs.getString("nombre"),
                    rs.getString("tipo"),
                    rs.getString("estado")
                );
                total++;
            }

            System.out.println("--------------------------------------------------");
            System.out.println("Total de hardware en inventario: " + total);
            System.out.println("==================================================");

        } catch (Exception e) {
            System.out.println("[ERROR]: " + e.getMessage());
        }
    }
}