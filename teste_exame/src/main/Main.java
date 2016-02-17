package main;

import java.util.Scanner;
import javax.swing.JFrame;
import models.IUtilizadorDao;
import models.Pessoa;
import models.Questionario;
import models.UtilizadorDaoHibernateImpl;
import models.Utilizador;
import user_interface.Login;
import util.HibernateUtil;
        
public class Main {
    
    public static void main(String[] args) {
        
        Login l = new Login();
        l.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    }
    
    //l.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    
    /*private static boolean numeroValido(String str) {
        
        //String[] numeros = {"1", "2", "3", "4", "5", "6", "7"};
        String[] numeros = {"1", "2"};
        
        for (int i = 0; i < numeros.length; i++) { if (numeros[i].equalsIgnoreCase(str)) { return true; } }
        return false;
    }
    
    public static void main(String[] args) {
        
        Scanner lerTeclado = new Scanner(System.in);
        String continuarSair = "";
        int opcao = 0;
        
        while (true) {
            while ((opcao < 1) || (opcao > 2)) { // Obriga ao utilizador inserir uma opção valida (1 ou 2).
                System.out.println("**************************************************************\n");
                System.out.println("--- INÍCIO DA APLICAÇÃO TESTE DE INFORMÁTICA.       ----------\n");
                System.out.println("**************************************************************\n");
                System.out.println("-- ESCOLHA A OPÇÃO QUE DESEJA INTERVALO [1 - 2]       --------\n");
                System.out.println("-- 1 => LOGUIN - ACEDER COMO UTILIZADOR REGISTRADO.   --------\n");
                System.out.println("-- 2 => REGISTRAR UTILIZADOR NA BASE DE DADOS         --------\n");
                System.out.println("**************************************************************\n");
              
                opcao = lerTeclado.nextInt();
                if ((opcao < 1) || (opcao > 2)) { System.out.println("ERRO => APENAS SÃO PERMITIDOS CARACTERES NUMÉRICOS [1 - 2].\n"); }
            } 
            
            Utilizador user = new Utilizador();
            if ((opcao == 1) && (user.login())) {
                Questionario quest = new Questionario();
                // Se não é o administrador mostramos o QUESTIONARIO.
                opcao = 0;
                if (!user.getEmail().equalsIgnoreCase("admin@gmail.com")) {
                    while ((opcao < 1) || (opcao > 2)) { // Obriga ao utilizador inserir uma opção valida (1 ou 2).
                        System.out.println("***********************************************************\n");
                        System.out.println("-------------- MOSTRAR QUESTIONARIO.       ----------------\n");
                        System.out.println("----------- SELECIONE O NÍVEL DE DIFICULTADE --------------\n");
                        System.out.println("***********************************************************\n");
                        System.out.println("-- ESCOLHA A OPÇÃO QUE DESEJA INTERVALO [1 - 2]       -----\n");
                        System.out.println("-- 1 => SEM NÍVEL DE DIFICULTADE.                 ---------\n");
                        System.out.println("-- 2 => COM NÍVEL DE DIFICULTADE. RESTA (-0.5) POR PERGUNTA -");
                        System.out.println("-- ERRADA E MOSTRA O DOBRO DE PERGUNTAS QUE SE NÃO TIVER ----");
                        System.out.println("-- NÍVEL DE DIFICULDADE. ------------------------------------");
                        System.out.println("***********************************************************\n");
                        
                        opcao = lerTeclado.nextInt();
                        if ((opcao < 1) || (opcao > 2)) { System.out.println("ERRO => APENAS SÃO PERMITIDOS CARACTERES NUMÉRICOS [1 - 2].\n"); }
                    }
                    
                    switch (opcao) {
                        case 1: {
                            quest.mostrarQuestionario(false);
                            break;
                        }
                        case 2: {
                            quest.mostrarQuestionario(true);
                            break;
                        }
                        default: { System.out.println("ERRO => NÃO EXISTE O NÍVEL DE DIFICULDADE\n."); }
                    } 
                }
                else { // UTILIDADES PARA O ADMINISTRADOR DA APLICAÇÃO.
                    while ((opcao < 1) || (opcao > 2)) { // Obriga ao utilizador inserir uma opção valida (1 ou 2).
                        System.out.println("**************************************************************\n");
                        System.out.println("--- UTILIDADES PARA O ADMINISTRADOR DA APLICAÇÃO.   ----------\n");
                        System.out.println("**************************************************************\n");
                        System.out.println("-- ESCOLHA A OPÇÃO QUE DESEJA INTERVALO [1 - 2]       --------\n");
                        System.out.println("-- 1 => INSERIR PERGUNTAS BD.                         --------\n");
                        System.out.println("-- 2 => GUARDAR UM FICHEIRO COM o QUOSTIONARIO NA BD. --------\n");
                        System.out.println("**************************************************************\n");
                        
                        opcao = lerTeclado.nextInt();
                        if ((opcao < 1) || (opcao > 2)) { System.out.println("ERRO => APENAS SÃO PERMITIDOS CARACTERES NUMÉRICOS [1 - 2].\n"); }
                    }
                    
                    switch (opcao) {
                        case 1: {
                            quest.inserirInfoBD();
                            break;
                        }
                        case 2: {
                            quest.guardarFicheiroBD();
                            break;
                        }
                        default: { System.out.println("ERRO => NÃO EXISTE A OPÇÃO\n."); }
                    }
                }
            }
            else { if (opcao == 2) { user.criarUtilizadorBD(); } } // REGISTRAR UTILIZADOR.

            System.out.println("\nDESEJA SAIR DO PROGRAMA (s/n)?\n");
            continuarSair = lerTeclado.next();
            
            if (continuarSair.equalsIgnoreCase("s")) { // SAIR DO PROGRAMA (s/n)?
                System.out.println("\nFIM PROGRAMA ESTRUTURA QUESTIONARIO.\n");
                break; 
            }
            opcao = 0;
        }
    }*/
}