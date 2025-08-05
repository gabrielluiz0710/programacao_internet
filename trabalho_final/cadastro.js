document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-cadastro');
    const nomeInput = document.getElementById('nome');
    const cpfInput = document.getElementById('cpf');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');
    const telefoneInput = document.getElementById('telefone');

    const mascaraCPF = (valor) => {
        return valor
            .replace(/\D/g, '') 
            .replace(/(\d{3})(\d)/, '$1.$2') 
            .replace(/(\d{3})(\d)/, '$1.$2') 
            .replace(/(\d{3})(\d{1,2})$/, '$1-$2') 
            .substring(0, 14);
    };

    const mascaraTelefone = (valor) => {
        let v = valor.replace(/\D/g, '');
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        if (v.length > 13) { 
            v = v.replace(/(\d{5})(\d)/, '$1-$2');
        } else {
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
        }
        return v.substring(0, 15); 
    };

    cpfInput.addEventListener('input', (e) => {
        e.target.value = mascaraCPF(e.target.value);
    });

    telefoneInput.addEventListener('input', (e) => {
        e.target.value = mascaraTelefone(e.target.value);
    });


    const exibirErro = (input, mensagem) => {
        const errorSpan = input.nextElementSibling;
        errorSpan.textContent = mensagem;
        input.classList.add('error');
    };

    const limparErro = (input) => {
        const errorSpan = input.nextElementSibling;
        errorSpan.textContent = '';
        input.classList.remove('error');
    };

    const validarNome = () => {
        if (nomeInput.value.trim().length < 3) {
            exibirErro(nomeInput, 'O nome deve ter pelo menos 3 caracteres.');
            return false;
        }
        limparErro(nomeInput);
        return true;
    };

    const validarCPF = () => {
        const cpfNumerico = cpfInput.value.replace(/\D/g, '');
        if (cpfNumerico.length !== 11) {
            exibirErro(cpfInput, 'O CPF deve conter 11 dígitos.');
            return false;
        }
        limparErro(cpfInput);
        return true;
    };

    const validarEmail = () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            exibirErro(emailInput, 'Formato de e-mail inválido.');
            return false;
        }
        limparErro(emailInput);
        return true;
    };

    const validarSenha = () => {
        if (senhaInput.value.length < 6) {
            exibirErro(senhaInput, 'A senha deve ter pelo menos 6 caracteres.');
            return false;
        }
        limparErro(senhaInput);
        return true;
    };

    const validarTelefone = () => {
        const telefoneNumerico = telefoneInput.value.replace(/\D/g, '');
        if (telefoneNumerico.length < 10 || telefoneNumerico.length > 11) {
            exibirErro(telefoneInput, 'O telefone deve conter 10 ou 11 dígitos (com DDD).');
            return false;
        }
        limparErro(telefoneInput);
        return true;
    };

    nomeInput.addEventListener('blur', validarNome);
    cpfInput.addEventListener('blur', validarCPF);
    emailInput.addEventListener('blur', validarEmail);
    senhaInput.addEventListener('blur', validarSenha);
    telefoneInput.addEventListener('blur', validarTelefone);

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const nomeValido = validarNome();
        const cpfValido = validarCPF();
        const emailValido = validarEmail();
        const senhaValida = validarSenha();
        const telefoneValido = validarTelefone();

        if (nomeValido && cpfValido && emailValido && senhaValida && telefoneValido) {
            window.location.href = 'central-user.html';
        }
    });
});