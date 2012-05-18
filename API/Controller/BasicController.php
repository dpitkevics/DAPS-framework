<?php

/**
 * Description of DefaultController
 *
 * @author danpit134
 */
class BasicController extends VanillaController{   
    function view() {
        $this->set('title', 'Default Template');
        $this->set('pageTitle', 'Home');
        $link = $this->create("HTML")->link("Test", "basic/DBtest/", 1, "Are you really want to continue?");
        $this->set('link', $link);
    }
    
    function DBtest($id = NULL) {
        $this->set('title', 'DB test page');
        $this->set('pageTitle', 'DB test page');
        $this->Basic->id = $id;
        $this->Basic->showHasOne();
        $this->Basic->showHMABTM();
        $DBtest = $this->Basic->search();
        $this->set('DBtest', $DBtest);
    }
    
    function form() {
        $this->set('title', 'Form test page');
        $this->set('pageTitle', 'Form test page');
        $form = $this->create("form")->set(BASE_DIR."basic/form/");
        $form->addField("text", "name", "Vārds", null, true);
        $form->addField("text", "lastname", "Uzvārds");
        $form->addField("radio", "job", "Web", "web");
        $form->addField("radio", "job", "Sport", "sport");
        $form->addField("checkbox", "hobby", "Basketball", "bball");
        $form->addField("checkbox", "hobby", "Football", "fball");
        $form->addField("textarea", "about", null, null, true);
        $form->addSubmit();
        $form->addReset("Izdzēst");
        $result = $form->finish();
        $this->set('form', $result);
        
        if ($form->isSubmitted()) {
            $form->updateDB();
            $this->set('res', 'Data stored in DB');
        } else
            $this->set('res', NULL);
    }
    
    function session() {
        $this->set('title', 'Session test page');
        $this->set('pageTitle', 'Session test page');
        $session = $this->create("session");
        if (!$session->isActive("test"))
            $session->set("test", "Testing session");
        
        $form = $this->create('form')->set(BASE_DIR."basic/session/");
        $form->addField("text", "value", "Session value:", null, true);
        $form->addSubmit();
        $result = $form->finish();
        $this->set('sesForm', $result);
        
        if ($form->isSubmitted()) {
            $array = $form->getAsArray();
            $session->set("test", $array["value"]);
            $this->set('res', "Session updated");
        } else {
            $this->set('res', NULL);
        }
        
        $ses = $session->get("test");
        $this->set('session', $ses);
    }
    
    function cookies() {
        $this->set('title', 'Cookies test page');
        $this->set('pageTitle', 'Cookies test page');
        $cookies = $this->create('cookies');
        $form = $this->create('form');
        $form->set(BASE_DIR."basic/cookies/");
        $form->addField('text', 'value', 'Cookie value:', null, true);
        $form->addSubmit();
        $res = $form->finish();
        $this->set('form', $res);
        
        if ($form->isSubmitted()) {
            $res = $form->getAsArray();
            $cookies->set('test', $res["value"], "5 minute");
            $this->set('cookie', 'This cookie is set for 5 min: '. $cookies->get('test'));
        } else {
            if ($cookies->isActive('test'))
                $this->set('cookie', 'This cookie is set for 5 min: '. $cookies->get('test'));
            else
                $this->set('cookie', NULL);
        }
    }
    
    function table() {
        $this->set('title', 'Table test page');
        $this->set('pageTitle', 'Table test page');
        $table = $this->create('table');
        
        $DBtest = $this->Basic->search();
        
        $table->set("border=1");
        $table->addDBArray($DBtest);
        $res = $table->finish();
        $this->set('table', $res);
    }
}

