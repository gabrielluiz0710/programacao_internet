// --- 1. SELEÇÃO DOS ELEMENTOS DO DOM ---
// Seleciona o formulário pelo seu ID.
const form = document.getElementById('cadastro-form');

// Seleciona cada campo de input pelo seu ID.
const nomeInput = document.getElementById('nome');
const emailInput = document.getElementById('email');
const senhaInput = document.getElementById('senha');
const confirmarSenhaInput = document.getElementById('confirmar-senha');

// --- 2. ADIÇÃO DO "OUVINTE" DE EVENTO DE SUBMISSÃO ---
// Adiciona um evento que é disparado quando o usuário tenta enviar o formulário.
form.addEventListener('submit', function(event) {
    // A linha abaixo previne o comportamento padrão do formulário, que é recarregar a página.
    // Isso nos permite controlar o que acontece com JavaScript.
    event.preventDefault();

    // Chama a nossa função principal de validação.
    checkInputs();
});

// --- 3. FUNÇÃO PRINCIPAL DE VALIDAÇÃO ---
function checkInputs() {
    // Pega os valores de cada campo e remove espaços em branco do início e do fim com trim().
    const nomeValue = nomeInput.value.trim();
    const emailValue = emailInput.value.trim();
    const senhaValue = senhaInput.value.trim();
    const confirmarSenhaValue = confirmarSenhaInput.value.trim();
    
    // Variável de controle para saber se o formulário está válido.
    let formIsValid = true;

    // --- Validação do campo Nome ---
    if (nomeValue === '') {
        // Se o valor for vazio, mostra uma mensagem de erro.
        setErrorFor(nomeInput, 'Este campo é obrigatório.');
        formIsValid = false;
    } else {
        // Se o valor for válido, remove a mensagem de erro.
        setSuccessFor(nomeInput);
    }

    // --- Validação do campo E-mail ---
    if (emailValue === '') {
        setErrorFor(emailInput, 'Este campo é obrigatório.');
        formIsValid = false;
    } else {
        setSuccessFor(emailInput);
    }

    // --- Validação do campo Senha ---
    if (senhaValue === '') {
        setErrorFor(senhaInput, 'Este campo é obrigatório.');
        formIsValid = false;
    } else {
        setSuccessFor(senhaInput);
    }

    // --- Validação do campo Confirmação de Senha ---
    if (confirmarSenhaValue === '') {
        setErrorFor(confirmarSenhaInput, 'Este campo é obrigatório.');
        formIsValid = false;
    } else if (senhaValue !== confirmarSenhaValue) {
        // Validação extra: verifica se as senhas são iguais.
        setErrorFor(confirmarSenhaInput, 'As senhas não coincidem.');
        formIsValid = false;
    } else {
        setSuccessFor(confirmarSenhaInput);
    }
    
    // Se, após todas as validações, a variável formIsValid continuar true...
    if (formIsValid) {
        alert('Cadastro realizado com sucesso!');
        form.reset(); // Limpa o formulário.
    }
}

// --- 4. FUNÇÕES AUXILIARES PARA MOSTRAR/LIMPAR ERROS ---

/**
 * Função para mostrar uma mensagem de erro.
 * @param {HTMLElement} input - O elemento input que tem o erro.
 * @param {string} message - A mensagem de erro a ser exibida.
 */
function setErrorFor(input, message) {
    // Encontra o elemento '.form-group' que é o "pai" do input.
    const formGroup = input.parentElement;
    // Encontra o 'span.error-message' dentro deste grupo.
    const errorMessageSpan = formGroup.querySelector('.error-message');

    // Define o texto do span como a mensagem de erro.
    errorMessageSpan.innerText = message;
}

/**
 * Função para limpar a mensagem de erro de um campo.
 * @param {HTMLElement} input - O elemento input que está correto.
 */
function setSuccessFor(input) {
    const formGroup = input.parentElement;
    const errorMessageSpan = formGroup.querySelector('.error-message');

    // Limpa o texto do span de erro.
    errorMessageSpan.innerText = '';
}