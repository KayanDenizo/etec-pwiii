# Loja Etim: catálogo de produtos com login

Protótipo com **menu, login e CRUD** em PHP + MySQL (PDO): CRUD completo de produtos (com imagens) e de usuários. As páginas internas usam uma NavBar do Bootstrap 5.

As duas classes seguem o modelo estudado em aula: atributos privados, um `conecta()` que abre o PDO e devolve `true`/`false`, e cada operação feita com `prepare` → `bindValue` → `execute`.

| Classe | Métodos |
|---|---|
| `Produto` | `conecta`, `enviarProduto`, `buscarProdutos`, `buscarProdutosPorId`, `alterarProduto`, `apagarImagem`, `apagarProduto` |
| `Usuario` | `conecta`, `inserirUsuario`, `alterarUsuario`, `apagarUsuario`, `listarUsuarios`, `buscarUsuarioPorId`, `emailCadastrado`, `logar` |

## Como rodar

1. Copie esta pasta para o `htdocs` do XAMPP e inicie o Apache e o MySQL.
2. Rode o script [`sql/banco.sql`](sql/banco.sql) no phpMyAdmin. Ele cria o banco `loja_etim` com as tabelas `produtos`, `imagens` e `usuarios`. Se o banco já existir, só cria o que estiver faltando.
3. Acesse `http://localhost/<pasta>/` e entre com o usuário inicial:
   - **E-mail:** `admin@etim.com`
   - **Senha:** `admin123`

## Menus da NavBar

| Menu | Opção | Página |
|---|---|---|
| Início | Página principal com atalhos e contadores | `principal.php` |
| Produtos | Produtos cadastrados | `produtos.php` (detalhes em `exibir_produto.php`) |
| Produtos | Cadastrar produto com imagens | `produto_cadastrar.php` |
| Produtos | Atualizar produto (dados, remover e adicionar imagens) | `produto_atualizar.php` |
| Produtos | Deletar produto (apaga também as imagens) | `produto_deletar.php` |
| Produtos | Catálogo estático | `produtosestatico.php` |
| Usuários | Listar usuários | `usuarios.php` |
| Usuários | Inserir usuário | `usuario_inserir.php` |
| Usuários | Atualizar usuário | `usuario_atualizar.php` |
| Usuários | Deletar usuário | `usuario_deletar.php` |
| (direita) | Sair | `logout.php` |

Todas essas páginas exigem login. Sem sessão, o usuário volta para o `index.php`.

## Estrutura

```
classes/
  Produto.class.php     classe dos produtos e imagens (cadastrar, listar, alterar, apagar)
  Usuario.class.php     classe dos usuários (inserir, alterar, apagar, listar, login)
includes/
  auth.php              protege as páginas internas (exige login)
  head.php              metas, fontes, Bootstrap e css/painel.css
  navbar.php            NavBar do Bootstrap com os menus
  mensagem.php          alerta de sucesso/erro depois de um redirect
  upload.php            salva as fotos enviadas e apaga arquivos de imagem
  rodape.php            rodapé + JavaScript do Bootstrap
css/
  painel.css            tema das páginas internas
  estilo.css            estilo do formulário de cadastro de produto
index.php               login
```

A documentação da classe `Usuario`, com o código completo e exemplos de uso, está em [`classe-usuario.md`](classe-usuario.md).

## Atividades anteriores

- [x] Criar arquivo `produtosestatico.php`
- [x] Nele, criar o layout para exibir produtos
- [x] Pode criar ou usar o meu design
