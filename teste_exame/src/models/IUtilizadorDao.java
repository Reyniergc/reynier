package models;

import java.util.List;

public interface IUtilizadorDao {
    
    public Utilizador validarUser(String email, String password);
    //public Utilizador findByEmail(String email);
    
    public Utilizador recuperarEmail(String email);
    
    public List<Questionario> mostrarQuestionario();
    
    public Utilizador findById(Integer id);

    public List<Utilizador> findAll();

    //public void save(Utilizador user);
    public boolean save(Utilizador user);
    
    public boolean save(Questionario quest);

    public void delete(Utilizador user);
    
}
