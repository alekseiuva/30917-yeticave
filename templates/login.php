<form
    class="<?= count($formErrors) > 0 ? 'form form--invalid container' : 'form container' ?>"
    action="login.php"
    method="post"
> <!-- form--invalid -->
    <h2>Вход</h2>

    <div
        class="<?= isset($formErrors['email']) ?
            'form__item form__item--invalid' :
            'form__item'; ?>"
    >
        <label for="email">E-mail*</label>
        <input
            id="email"
            type="text"
            name="email"
            placeholder="Введите e-mail"
            value="<?= isset($values['email']) ? htmlspecialchars($values['email']) : ''; ?>"
            required
        >
        <span class="form__error">
            <?= $formErrors['email'] ?? ''; ?>
        </span>
    </div>

    <div
        class="<?= isset($formErrors['password']) ?
            'form__item form__item--invalid' :
            'form__item'; ?>"
    >
        <label for="password">Пароль*</label>
        <input
            id="password"
            type="password"
            name="password"
            placeholder="Введите пароль"
            required>
        <span class="form__error">
            <?= $formErrors['password'] ?? ''; ?>
        </span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
