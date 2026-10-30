document.addEventListener("DOMContentLoaded", () => {

    const scribble = document.querySelector(".hero-scribble");

    if (scribble) {
        requestAnimationFrame(() => {
            scribble.classList.add("animate");
        });
    }

    const menuToggle = document.querySelector(".menu-toggle");
    const header = document.querySelector(".hero-header");
    const nav = document.querySelector(".courses-nav");
    const actions = document.querySelector(".courses-actions");

    if (menuToggle) {

        menuToggle.addEventListener("click", () => {

            if (header) {
                header.classList.toggle("mobile-open");
            }

            if (nav) {
                nav.classList.toggle("mobile-show");
            }

            if (actions) {
                actions.classList.toggle("mobile-show");
            }

        });

    }

    const reveals = document.querySelectorAll(".reveal");

    if (reveals.length > 0) {

        const observer = new IntersectionObserver((entries) => {

            entries.forEach((entry, index) => {

                if (entry.isIntersecting) {

                    setTimeout(() => {

                        entry.target.classList.add("show");

                    }, index * 120);

                }

            });

        }, {

            threshold: 0.18

        });

        reveals.forEach(item => observer.observe(item));

    }

    const perguntas = document.querySelectorAll(".faq-question");

    perguntas.forEach((pergunta) => {

        pergunta.addEventListener("click", () => {

            const item = pergunta.parentElement;

            document.querySelectorAll(".faq-item").forEach((faq) => {

                if (faq !== item) {

                    faq.classList.remove("active");

                }

            });

            item.classList.toggle("active");

        });

    });

    const formulario = document.getElementById("contactForm");

    if (!formulario) return;

    const nome = document.getElementById("nome");
    const email = document.getElementById("email");
    const telefone = document.getElementById("telefone");
    const instituicao = document.getElementById("instituicao");
    const assunto = document.getElementById("assunto");
    const mensagem = document.getElementById("mensagem");

    const contador = document.getElementById("charCount");
    const botao = document.getElementById("submitBtn");

    const limite = 500;

    mensagem.addEventListener("input", () => {

        let total = mensagem.value.length;

        if (total > limite) {

            mensagem.value = mensagem.value.substring(0, limite);

            total = limite;

        }

        contador.textContent = total + " / " + limite;

        contador.classList.remove("near-limit");
        contador.classList.remove("at-limit");

        if (total >= 400 && total < limite) {

            contador.classList.add("near-limit");

        }

        if (total >= limite) {

            contador.classList.add("at-limit");

        }

    });

    telefone.addEventListener("input", () => {

        let valor = telefone.value.replace(/\D/g, "");

        if (valor.length > 11) {

            valor = valor.substring(0,11);

        }

        if (valor.length > 10) {

            valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");

        }

        else if (valor.length > 6) {

            valor = valor.replace(/^(\d{2})(\d{4})(\d+)$/, "($1) $2-$3");

        }

        else if (valor.length > 2) {

            valor = valor.replace(/^(\d{2})(\d+)$/, "($1) $2");

        }

        telefone.value = valor;

    });

    function mostrarErro(campo, mensagem){

        campo.classList.remove("is-valid");
        campo.classList.add("is-error");

        const erro = document.getElementById("erro-" + campo.id);

        if(erro){

            erro.textContent = mensagem;

        }

    }

    function limparErro(campo){

        campo.classList.remove("is-error");
        campo.classList.add("is-valid");

        const erro = document.getElementById("erro-" + campo.id);

        if(erro){

            erro.textContent = "";

        }

    }
        function validarNome(){

        if(nome.value.trim() === ""){

            mostrarErro(nome,"Informe seu nome.");
            return false;

        }

        if(nome.value.trim().length < 3){

            mostrarErro(nome,"O nome deve ter pelo menos 3 caracteres.");
            return false;

        }

        limparErro(nome);
        return true;

    }

    function validarEmail(){

        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(email.value.trim() === ""){

            mostrarErro(email,"Informe seu email.");
            return false;

        }

        if(!regex.test(email.value)){

            mostrarErro(email,"Email inválido.");
            return false;

        }

        limparErro(email);
        return true;

    }

    function validarTelefone(){

        if(telefone.value.replace(/\D/g,"").length < 10){

            mostrarErro(telefone,"Telefone inválido.");
            return false;

        }

        limparErro(telefone);
        return true;

    }

    function validarInstituicao(){

        if(instituicao.value.trim().length < 2){

            mostrarErro(instituicao,"Informe a instituição.");
            return false;

        }

        limparErro(instituicao);
        return true;

    }

    function validarAssunto(){

        if(assunto.value === ""){

            mostrarErro(assunto,"Selecione um assunto.");
            return false;

        }

        limparErro(assunto);
        return true;

    }

    function validarMensagem(){

        if(mensagem.value.trim().length < 15){

            mostrarErro(mensagem,"Digite uma mensagem com pelo menos 15 caracteres.");
            return false;

        }

        limparErro(mensagem);
        return true;

    }

    nome.addEventListener("blur", validarNome);
    email.addEventListener("blur", validarEmail);
    telefone.addEventListener("blur", validarTelefone);
    instituicao.addEventListener("blur", validarInstituicao);
    assunto.addEventListener("blur", validarAssunto);
    mensagem.addEventListener("blur", validarMensagem);

    formulario.addEventListener("submit", function(e){

        let valido = true;

        if(!validarNome()){

            valido = false;

        }

        if(!validarEmail()){

            valido = false;

        }

        if(!validarTelefone()){

            valido = false;

        }

        if(!validarInstituicao()){

            valido = false;

        }

        if(!validarAssunto()){

            valido = false;

        }

        if(!validarMensagem()){

            valido = false;

        }

        if(!valido){

            e.preventDefault();

            const primeiroErro = document.querySelector(".is-error");

            if(primeiroErro){

                primeiroErro.focus();

            }

            return;

        }

        botao.disabled = true;
        botao.textContent = "Enviando...";

    });

});