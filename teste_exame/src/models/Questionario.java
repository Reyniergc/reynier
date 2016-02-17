package models;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.logging.Level;
import java.util.logging.Logger;

public class Questionario implements Serializable {
    
    private int id, respostaCorreta;
    private String pergunta, resposta1, resposta2, resposta3, resposta4;

    public Questionario() {
        
        pergunta = "";
        resposta1 = "";
        resposta2 = "";
        resposta3 = "";
        resposta4 = "";
        respostaCorreta = 0;
    }
    
    public Questionario(String pergunta, String resposta1, String resposta2, 
                        String resposta3, String resposta4, int respostaCorreta) {
        
        this.pergunta  = pergunta;
        this.resposta1 = resposta1;
        this.resposta2 = resposta2;
        this.resposta3 = resposta3;
        this.resposta4 = resposta4;
        this.respostaCorreta = respostaCorreta;
    }
    
    public int getId() { return id; }

    private void setId(int id) { this.id = id; }

    public int getRespostaCorreta() { return respostaCorreta; }

    public void setRespostaCorreta(int respostaCorreta) { this.respostaCorreta = respostaCorreta; }

    public String getPergunta() { return pergunta; }

    public void setPergunta(String pergunta) { this.pergunta = pergunta; }

    public String getResposta1() { return resposta1; }

    public void setResposta1(String resposta1) { this.resposta1 = resposta1; }

    public String getResposta2() { return resposta2; }

    public void setResposta2(String resposta2) { this.resposta2 = resposta2; }

    public String getResposta3() { return resposta3; }

    public void setResposta3(String resposta3) { this.resposta3 = resposta3; }

    public String getResposta4() { return resposta4; }

    public void setResposta4(String resposta4) { this.resposta4 = resposta4; }

    // Gera respostas aleatorias de cada pergunta.
    private String[] vetorAleatorio(String[] vetor, int indiceResposta) {
        
        --indiceResposta;
        int i = 0, j = 0;
        ArrayList<Integer> valoresGerados = new ArrayList<Integer>();
        String[] vetorAleatorio = new String[5];
        
        for (;;) {
            if (valoresGerados.size() == 4) { break; }
            
            j = (int)(Math.random()*(5 - 1)) + 0;
            if (!valoresGerados.contains(j)) { 
                vetorAleatorio[i++] = vetor[j];
                valoresGerados.add(j);
                if (j == indiceResposta) { vetorAleatorio[4] = "" + i; }
            }
        }
        return vetorAleatorio;
    }
    
    private boolean numeroValido(String str) {
        
        String[] numeros = {"1", "2", "3", "4"};
        
        for (int i = 0; i < numeros.length; i++) { if (numeros[i].equalsIgnoreCase(str)) { return true; } }
        return false;
    }
    
    public void mostrarQuestionario(boolean nDificuldade) {
        
        Scanner lerTeclado = new Scanner(System.in);
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
        List<Questionario> quest = eventoDao.mostrarQuestionario();
        String[] vRespostasAleatorias = new String[5];
        String respostaAux = "";
        int i = 0, numeroPerguntas = 0;
        float notaFinal = 0.f;
        
        // Nivel dificuldade falso mostamos a metade das perguntas.
        if (!nDificuldade) { numeroPerguntas = quest.size() / 2 ; } 
        
        System.out.println("RESPONDA AS SEGUINTES PERGUNTAS.\n");
        System.out.print("INÍCIO QUESTIONÁRIO.\n");
        
        for (Questionario questOb : quest) {
            if ((!nDificuldade) && (i == numeroPerguntas)) { break; }
            
            System.out.println("\n" + (++i) + "ª PERGUNTA.\n");
            System.out.println(questOb.getPergunta());
             
            vRespostasAleatorias[0] = questOb.getResposta1();
            vRespostasAleatorias[1] = questOb.getResposta2();
            vRespostasAleatorias[2] = questOb.getResposta3();
            vRespostasAleatorias[3] = questOb.getResposta4();
            vRespostasAleatorias = this.vetorAleatorio(vRespostasAleatorias, questOb.getRespostaCorreta());
            
            // Mostra as quatro possiveis respostas de uma pergunta.
            System.out.println("\n 1)" + vRespostasAleatorias[0]);  
            System.out.println(" 2)" + vRespostasAleatorias[1]);
            System.out.println(" 3)" + vRespostasAleatorias[2]);
            System.out.println(" 4)" + vRespostasAleatorias[3]);
                
            while (true) { // Obriga o utilizador inserir un número de 1 a 4.
                respostaAux = lerTeclado.next();
                if (numeroValido(respostaAux)) { break; }
                else { System.out.println("ERRO => DEVE INSERIR UM VALOR NO INTERVALOR [1 - 4].\n"); }
            }
                
            // Respota correta.
            if (Integer.parseInt(respostaAux) == Integer.parseInt(vRespostasAleatorias[4])) { 
                System.out.println("Parabéns!!! A sua respota é correta.\n");
                notaFinal += 5; 
            }
            else { 
                System.out.println("ERRO => Infelizmente a sua respota não é correta.\n");
                // Se o nivel de dificuldade é verdadeiro restamos 0.5 valores a nota final.
                if (nDificuldade) { notaFinal -= 0.5; } 
            }
        }
        
        System.out.println("\nSUA CLASSIFICAÇÃO FINAL NO INTERVALO DE [0 - 20] DEPOIS DE TERMINAR O QUESTIONÁRIO.");
        System.out.println("\nNOTA FINAL DO TESTE = " + notaFinal + ".");
        
        // Supera o exame se tem uma nota maior ou igual a 10 no ultimo teste.
        if (notaFinal >= 10) { System.out.println("\nParabéns!!! Você superou o teste.\n"); }
        else { System.out.println("\nInfelizmente você não superou o teste."); }
    } 
        
    public void inserirInfoBD() {
        
        Scanner lerTeclado = new Scanner(System.in);        
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
        
        System.out.print("INSIRA A PERGUNTA.\n");
        this.setPergunta(lerTeclado.nextLine());        
        
        System.out.println("\nINSIRA HA 1 RESPOSTA.");
        this.setResposta1(lerTeclado.nextLine());
        
        System.out.println("\nINSIRA HA 2 RESPOSTA.");
        this.setResposta2(lerTeclado.nextLine());
        
        System.out.println("\nINSIRA HA 3 RESPOSTA.");
        this.setResposta3(lerTeclado.nextLine());
        
        System.out.println("\nINSIRA HA 4 RESPOSTA.");
        this.setResposta4(lerTeclado.nextLine());        
        
        System.out.println("\nINSIRA HA RESPOSTA CORRETA.");
        this.setRespostaCorreta(lerTeclado.nextInt());
        
        eventoDao.save(this);
    }  
    
    // Inserta as perguntas e respostas desde um ficheiro na base de dados.
    public void guardarFicheiroBD() {
        
        IUtilizadorDao eventoDao = new UtilizadorDaoHibernateImpl();
        Scanner lerTeclado = new Scanner(System.in);
        String[] vQuestionario = new String[5];
        File ficheiro;
        
        System.out.println("Insira o caminho do ficheiro que contêm o Questionario.\n");
        ficheiro = new File(lerTeclado.nextLine());
        
        try {
            BufferedReader br = new BufferedReader(new FileReader(ficheiro));
            String linhaLeida = "";
            
            while (true) {
                linhaLeida = br.readLine(); // Lemos uma linha do ficheiro.
                if (linhaLeida == null) { break; } // Verificamos se já chegamos ao fim do ficheiro.
                
                eventoDao.save(new Questionario(linhaLeida, br.readLine(), br.readLine(), 
                        br.readLine(), br.readLine(), Integer.parseInt(br.readLine()))); 
            }
            br.close();
        } catch (IOException ex) {
            Logger.getLogger(Questionario.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}