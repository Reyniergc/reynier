package models;

import java.sql.SQLException;
import java.util.List;
import javax.swing.JOptionPane;
import org.hibernate.Transaction;
import org.hibernate.HibernateException;
import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.criterion.Restrictions;
import util.HibernateUtil;

public class UtilizadorDaoHibernateImpl implements IUtilizadorDao {
    
    @Override
    public Utilizador findById(Integer id) {

        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Utilizador user = null;
        Transaction tx = null;

        try {
            tx = session.beginTransaction();
            user = (Utilizador)session.load(Utilizador.class, id);
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
            e.printStackTrace();
        } finally {
            //session.close();
        }
        return user;
    }
    
    @Override
    //public Utilizador findByEmail(String email) {
    public Utilizador validarUser(String email, String password) {

        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Utilizador user = null;
        Transaction tx = null;

        try {
            tx = session.beginTransaction();
            user = (Utilizador)session.createCriteria(Utilizador.class).
                    add(Restrictions.eq("email", email)).add(Restrictions.eq("password", password)).
                    uniqueResult();
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
        } finally { }
        
        return user;
    }
    
    @Override
    //public Utilizador findByEmail(String email) {
    public Utilizador recuperarEmail(String email) {

        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Utilizador user = null;
        Transaction tx = null;

        try {
            tx = session.beginTransaction();
            user = (Utilizador)session.createCriteria(Utilizador.class).
                    add(Restrictions.eq("email", email)).uniqueResult();
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
        } finally { }
        
        return user;
    }
    
    @Override
    public List<Questionario> mostrarQuestionario() {
        
        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();
        
        List<Questionario> quest = null;
        Transaction tx = null;
        
        try {
            tx = session.beginTransaction();
            Query query = session.createQuery("SELECT q FROM models.Questionario q ORDER BY RAND()");
            //Query query = session.createQuery("FROM models.Questionario ORDER BY RAND()").setMaxResults(3);
            //Query query = session.createSQLQuery("SELECT * FROM questionarios LIMIT 2").addEntity(Questionario.class);
            quest = query.list();
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
            e.printStackTrace();
        } finally {
            session.close();
        }
        return quest;
    }
    
    @Override
    public List<Utilizador> findAll() {

        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();
        
        List<Utilizador> user = null;
        Transaction tx = null;
        
        try {
            tx = session.beginTransaction();
            Query query = session.createQuery("from Utilizador");
            user = query.list();
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
            e.printStackTrace();
        } finally {
            session.close();
        }
        return user;
    }

    @Override
    //public void save(Utilizador user) {
    public boolean save(Utilizador user) {
        
        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Transaction tx = null;
        try {
            tx = session.beginTransaction();
            session.saveOrUpdate(user);
            tx.commit();
        } catch (HibernateException e) {            
            if (tx != null) {
                tx.rollback();
            }
            return false;
            //e.printStackTrace(); 
        }
        finally { session.close(); }
        
        return true;
    }
    
    @Override
    public boolean save(Questionario quest) {
        
        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Transaction tx = null;
        try {
            tx = session.beginTransaction();
            session.saveOrUpdate(quest);
            tx.commit();
        } catch (HibernateException e) {            
            if (tx != null) {
                tx.rollback();
            }
            return false;
        }
        finally { session.close(); }
        
        return true;
    }

    @Override
    public void delete(Utilizador user) {
        
        SessionFactory sessions = HibernateUtil.getSessionFactory();
        Session session = sessions.openSession();

        Transaction tx = null;
        try {
            tx = session.beginTransaction();
            session.delete(user);
            tx.commit();
        } catch (HibernateException e) {
            if (tx != null) {
                tx.rollback();
            }
            e.printStackTrace();
        } finally {
            session.close();
        }
    }
}