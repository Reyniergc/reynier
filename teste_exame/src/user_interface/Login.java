package user_interface;

import java.awt.Color;
import java.awt.Container;
import java.awt.event.*;
import java.awt.event.ActionListener;
import javax.swing.*;
import models.Utilizador;

public class Login extends JFrame {
    
    JLabel lUser, lPass, lMensagem;
    JPasswordField pass;
    JTextField tUser;
    JButton button;
            
    public Login() {
        
        super("Login Utilizador");
        Container contentor = getContentPane(); 
        setLayout(null); 
        
        lUser = new JLabel("Utilizador :");  
        lUser.setBounds(90, 55, 70, 10);
        
        tUser = new JTextField();  
        tUser.setBounds(180, 55, 80, 20);
        
        lPass = new JLabel("Password :");  
        lPass.setBounds(90, 100, 70, 10);
        
        pass = new JPasswordField(5);
        pass.setBounds(180, 100, 80, 20);   
        
        lMensagem = new JLabel();
        lMensagem.setBounds(100, 170, 150, 20);
        
        button = new JButton("Login");
        button.setBounds(180, 140, 78, 20);
        
        button.addActionListener(new ActionListener() {
            @Override 
            public void actionPerformed(ActionEvent e) { 
                
                Utilizador user = new Utilizador();
                if (user.login(tUser.getText(), pass.getText())) { // Utilizador validado corretamente.
                    lMensagem.setForeground(Color.green);
                    lMensagem.setText("Usuario logueado.");
                }
                else { 
                    lMensagem.setForeground(Color.red);
                    lMensagem.setText("Error nos dados inseridos."); 
                }
            }
        });
        
        contentor.add(lUser);
        contentor.add(tUser);
        contentor.add(lPass);
        contentor.add(pass);
        contentor.add(lMensagem);
        contentor.add(button);
        
        setSize(400, 250);        
        setVisible(true); 
    }
}