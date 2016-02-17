package models;

import java.util.Scanner;
import java.util.logging.Logger;
import org.apache.commons.codec.digest.DigestUtils;
import org.apache.commons.mail.*;


public class Utilizador extends Pessoa {
    
    private String email, password;
    
    public Utilizador() {
        
        super();
        this.email = "";
        this.password = "";
    }
    
    public Utilizador(int idade, String nome, String apelidos, 
                      String email, String password) {
        
        super(idade, nome, apelidos);
        this.email = email;
        this.password = password;
    }

    public String getEmail() { return email; }

    public void setEmail(String email) { this.email = email; }

    public String getPassword() { return password; }

    public void setPassword(String password) { this.password = password; }
    
    /* Cria e guarda ha informação de um utilizador na base de datos utilizadores. */    
    public void criarUtilizadorBD() {
        
        Scanner lerTeclado = new Scanner(System.in);
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
         
        System.out.println("Insira a sua idade.");
        super.setIdade(lerTeclado.nextInt());
        
        lerTeclado.nextLine();
        System.out.println("\nInsira o seu nome.");
        super.setNome(lerTeclado.nextLine());
        System.out.println("\nInsira os seus apelidos.");
        super.setApelidos(lerTeclado.nextLine());
        
        System.out.println("\nInsira o email.");
        this.setEmail(lerTeclado.nextLine());
        System.out.println("\nInsira ha sua password.\n");
        this.setPassword(DigestUtils.md5Hex(lerTeclado.nextLine())); // Encriptamos a password.
        
        // Guardamos na base de datos o objeto apenas se o utilizador não existir na BD.
        if (eventoDao.save(this)) { System.out.println("Utilizador registrado corretamente.\n"); }
        else { System.out.println("Já existe um utilizador registrado com este email.\n"); }
    } 
    
    /* Verifica password e nome de utilizador. */
    //public boolean login() {
    public boolean login(String utilizador, String pass) {
        
        //Scanner lerTeclado = new Scanner(System.in);
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
        
        //System.out.println("\nInsira o email.");
        //this.setEmail(lerTeclado.nextLine());
        this.setEmail(utilizador);
        //System.out.println("\nInsira ha sua password.\n");
        //this.setPassword(DigestUtils.md5Hex(lerTeclado.nextLine())); // Encriptamos a password.
        this.setPassword(DigestUtils.md5Hex(pass));
        
        //Utilizador user = eventoDao.validarUser(this.email, this.password); 
        Utilizador user = eventoDao.validarUser(this.email, this.password); 
        if (user != null) { 
            //System.out.println("\nUsuario logueado.\n");
            return true; 
        }
        else { 
            //System.out.println("\nError nos dados inseridos.\n"); 
            return false; 
        }
    }
    
    /* Recuperar password utilizador. */
    public void recuperarPasswordUser() {
        
        /*Scanner lerTeclado = new Scanner(System.in);
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
        
        System.out.println("\nInsira o email para recuperar a sua password.");
        this.setEmail(lerTeclado.nextLine());
        
        //Utilizador user = eventoDao.recuperarEmail(this.email);
        
        /*if (user != null) { // Enviamos por email o password e nome de usuario.
            try {
                Email email = new SimpleEmail();
                email.setHostName("smtp.gmail.com");
                email.setSmtpPort(465);
                email.setAuthenticator(new DefaultAuthenticator("reynier.tellez@gmail.com", "reynier1991"));
                email.setSSLOnConnect(true);
                email.setFrom("reynier.tellez@gmail.com");
                email.setSubject("TestMail");
                email.setMsg("This is a test mail ... :-)");
                email.addTo(user.getEmail());
                email.send();
            } catch (EmailException ex) {
               Logger.getLogger(ex.toString());
            }
        }
        else { System.out.println("Não existe o email inserido.\n"); }*/
        try {
            Email email = new SimpleEmail();
            email.setHostName("smtp.gmail.com");
            email.setSmtpPort(465);
            email.setDebug(true);
            email.setAuthenticator(new DefaultAuthenticator("reynier.tellez@gmail.com", "sensuelle1991"));
            email.setSSLOnConnect(false);
            email.setFrom("reynier.tellez@gmail.com");
            email.setSubject("TestMail");
            email.setMsg("This is a test mail ... ");
            email.addTo("reynierlima@hotmail.com");
            email.send();
        } catch (EmailException ex) {
            Logger.getLogger(ex.toString());
        }
    }  
}