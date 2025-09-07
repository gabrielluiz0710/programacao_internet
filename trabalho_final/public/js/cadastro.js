document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-cadastro");
  const nomeInput = document.getElementById("nome");
  const cpfInput = document.getElementById("cpf");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("senha");
  const telefoneInput = document.getElementById("telefone");

  const mascaraCPF = (valor) => {
    return valor
      .replace(/\D/g, "")
      .replace(/(\d{3})(\d)/, "$1.$2")
      .replace(/(\d{3})(\d)/, "$1.$2")
      .replace(/(\d{3})(\d{1,2})$/, "$1-$2")
      .substring(0, 14);
  };

  const mascaraTelefone = (valor) => {
    let v = valor.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    if (v.length > 13) {
      v = v.replace(/(\d{5})(\d)/, "$1-$2");
    } else {
      v = v.replace(/(\d{4})(\d)/, "$1-$2");
    }
    return v.substring(0, 15);
  };

  cpfInput.addEventListener("input", (e) => {
    e.target.value = mascaraCPF(e.target.value);
  });

  telefoneInput.addEventListener("input", (e) => {
    e.target.value = mascaraTelefone(e.target.value);
  });

  const exibirErro = (input, mensagem) => {
    const errorSpan = input.nextElementSibling;
    errorSpan.textContent = mensagem;
    input.classList.add("error");
  };

  const limparErro = (input) => {
    const errorSpan = input.nextElementSibling;
    errorSpan.textContent = "";
    input.classList.remove("error");
  };

  const validarNome = () => {
    if (nomeInput.value.trim().length < 3) {
      exibirErro(nomeInput, "O nome deve ter pelo menos 3 caracteres.");
      return false;
    }
    limparErro(nomeInput);
    return true;
  };

  const validarCPF = () => {
    const cpfNumerico = cpfInput.value.replace(/\D/g, "");
    if (cpfNumerico.length !== 11) {
      exibirErro(cpfInput, "O CPF deve conter 11 dígitos.");
      return false;
    }
    limparErro(cpfInput);
    return true;
  };

  const validarEmail = () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput.value)) {
      exibirErro(emailInput, "Formato de e-mail inválido.");
      return false;
    }
    limparErro(emailInput);
    return true;
  };

  const validarSenha = () => {
    if (senhaInput.value.length < 6) {
      exibirErro(senhaInput, "A senha deve ter pelo menos 6 caracteres.");
      return false;
    }
    limparErro(senhaInput);
    return true;
  };

  const validarTelefone = () => {
    const telefoneNumerico = telefoneInput.value.replace(/\D/g, "");
    if (telefoneNumerico.length < 10 || telefoneNumerico.length > 11) {
      exibirErro(
        telefoneInput,
        "O telefone deve conter 10 ou 11 dígitos (com DDD)."
      );
      return false;
    }
    limparErro(telefoneInput);
    return true;
  };

  nomeInput.addEventListener("blur", validarNome);
  cpfInput.addEventListener("blur", validarCPF);
  emailInput.addEventListener("blur", validarEmail);
  senhaInput.addEventListener("blur", validarSenha);
  telefoneInput.addEventListener("blur", validarTelefone);

  form.addEventListener("submit", (event) => {
    event.preventDefault(); // Impede o envio tradicional

    // Executa todas as validações antes de enviar
    const isFormValid =
      validarNome() &&
      validarCPF() &&
      validarEmail() &&
      validarSenha() &&
      validarTelefone();
    if (!isFormValid) {
      alert("Por favor, corrija os erros no formulário.");
      return;
    }

    const submitButton = form.querySelector('button[type="submit"]');
    let messageDiv = document.getElementById("form-message");
    if (!messageDiv) {
      messageDiv = document.createElement("div");
      messageDiv.id = "form-message";
      form.insertAdjacentElement("afterend", messageDiv);
    }

    submitButton.disabled = true;
    submitButton.textContent = "Enviando...";

    // ############ MUDANÇA PRINCIPAL AQUI ############

    // Requisição AJAX com a API Fetch usando FormData
    fetch("/anunciante/cadastrar", {
      method: "POST",
      // IMPORTANTE: Ao usar FormData, NÃO defina o cabeçalho 'Content-Type'.
      // O navegador faz isso automaticamente e de forma correta.
      body: new FormData(form), // Enviando o formulário diretamente
    })
      .then((response) => {
        // Checamos se a resposta não foi OK antes de tentar ler como JSON
        if (!response.ok) {
          // Se o servidor retornar um erro (como 409 Conflict),
          // tentamos ler a mensagem de erro do JSON que o controller envia.
          return response.json().then((err) => {
            throw err;
          });
        }
        return response.json();
      })
      .then((result) => {
        messageDiv.textContent = result.message;
        if (result.success) {
          messageDiv.className = "message success";
          form.reset();
        } else {
          // Este 'else' pode não ser mais necessário por causa do 'catch' melhorado,
          // mas mantemos por segurança.
          messageDiv.className = "message error";
        }
      })
      .catch((error) => {
        console.error("Erro:", error);
        // Se o 'error' tiver uma mensagem (vindo do nosso 'throw' customizado), a usamos.
        // Senão, usamos a mensagem genérica.
        messageDiv.textContent =
          error.message || "Ocorreu um erro de comunicação. Tente novamente.";
        messageDiv.className = "message error";
      })
      .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Cadastrar";
      });
  });
});
