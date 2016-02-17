package models;

import java.io.Serializable;

public class Pessoa implements Serializable {
    
    private int id, idade;
    private String nome, apelidos;
    
    public Pessoa() {
        
        this.idade = 0;
        this.nome = "";
        this.apelidos = "";
    }
    
    public Pessoa(int idade, String nome, String apelidos) {
        
        this.idade = idade;
        this.nome = nome;
        this.apelidos = apelidos;
    }
    
    public int getId() { return id; }
    
    private void setId(int id) { this.id = id; }
    
    public String getNome() { return nome; }
    
    public void setNome(String nome) { this.nome = nome; }

    public String getApelidos() { return apelidos; }
    
    public void setApelidos(String apelidos) { this.apelidos = apelidos; }

    public int getIdade() { return idade; }
    
    public void setIdade(int idade) { this.idade = idade; }

    @Override
    public String toString() {
        return "Pessoa{" + "idade=" + idade + ", nome=" + nome + ", apelidos=" + apelidos + '}';
    }
}