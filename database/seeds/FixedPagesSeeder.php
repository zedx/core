<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Page;
use ZEDx\Models\Template;
use ZEDx\Models\Themepartial;
use ZEDx\Models\Widgetnode;

class FixedPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User
        $userLogin = $this->create('content', 'Identification des utilisateurs', 'userLogin', 'user.login', 'Identification des utilisateurs');
        $this->attachUserLoginWidgets($userLogin);

        $userRegister = $this->create('content', 'Inscription des utilisateurs', 'userRegister', 'user.register', 'Inscription des utilisateurs');
        $this->attachUserRegisterWidgets($userRegister);

        $userPasswordReset = $this->create('content', 'Réinitialisation du mot de passe', 'userPasswordReset', 'auth.password.reset', 'Réinitialisation du mot de passe');
        $this->attachUserPasswordResetWidgets($userPasswordReset);

        $userPasswordEmail = $this->create('content', 'Demande de réinitialisation du mot de passe', 'userPasswordEmail', 'auth.password.email', 'Demande de réinitialisation du mot de passe');
        $this->attachUserPasswordEmailWidgets($userPasswordEmail);

        //Ad
        $adShow = $this->create('ad', "Détail d'une annonce", 'adShow', 'ad.show', "Détail d'une annonce");
        $this->attachAdShowWidgets($adShow);

        $adSearch = $this->create('search', 'Moteur de recherche', 'adSearch', 'ad.search', 'Moteur de recherche');
        $this->attachAdSearchWidgets($adSearch);

        // User Ad

        $userAdList = $this->create('user', 'Annonces utilisateur', 'userAdList', 'user.ad.index', 'Annonces utilisateur');
        $this->attachUserAdListWidgets($userAdList);

        $userAdCreate = $this->create('user', 'Créer une annonce', 'userAdCreate', 'user.ad.create', 'Créer une annonce');
        $this->attachUserAdCreateWidgets($userAdCreate);

        $userAdEdit = $this->create('user', 'Modifier une annonce', 'userAdEdit', 'user.ad.edit', 'Modifier une annonce');
        $this->attachUserAdEditWidgets($userAdEdit);

        // User Adtype
        $userAdtypeList = $this->create('user', "Liste des types d'annonce", 'userAdtypeList', 'user.adtype.index', "Liste des types d'annonce");
        $this->attachUserAdtypeListWidgets($userAdtypeList);

        $userAdtypeCart = $this->create('user', "Achat type d'annonce", 'userAdtypeCart', 'user.adtype.cart', "Achat type d'annonce");
        $this->attachUserAdtypeCartWidgets($userAdtypeCart);

        // User Subscription
        $userSubscriptionList = $this->create('user', 'Liste des abonnements', 'userSubscriptionList', 'user.subscription.index', 'Liste des abonnements');
        $this->attachUserSubscriptionListWidgets($userSubscriptionList);

        $userSubscriptionCart = $this->create('user', "Achat d'un abonnement", 'userSubscriptionCart', 'user.subscription.cart', "Achat d'un abonnement");
        $this->attachUserSubscriptionCartWidgets($userSubscriptionCart);

        // User Profile
        $userEdit = $this->create('user', "Modifier l'utilisateur", 'userEdit', 'user.edit', "Modifier l'utilisateur");
        $this->attachUserEditWidgets($userEdit);

        // User Payment
        $paymentAccepted = $this->create('content', 'Paiement accepté', 'paymentAccepted', 'payment.accepted', 'Paiement accepté');
        $this->attachPaymentAcceptedWidgets($paymentAccepted);

        $paymentCancelled = $this->create('content', 'Paiement annulé', 'paymentCancelled', 'payment.cancelled', 'Paiement annulé');
        $this->attachPaymentCancelledWidgets($paymentCancelled);
    }

    protected function create($templateIdentifier, $name, $type, $shortcut, $description)
    {
        return Page::create([
            'name'        => $name,
            'type'        => $type,
            'shortcut'    => $shortcut,
            'description' => $description,
            'template_id' => Template::whereIdentifier($templateIdentifier)->first()->id,
        ]);
    }

    protected function attachUserLoginWidgets($userLogin)
    {
        $userLogin->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userLogin, 'content', 'Theme', 'UserLogin');
    }

    protected function attachUserRegisterWidgets($userRegister)
    {
        $userRegister->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userRegister, 'content', 'Theme', 'UserRegister');
    }

    protected function attachUserPasswordResetWidgets($userPasswordReset)
    {
        $userPasswordReset->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userPasswordReset, 'content', 'Theme', 'userPasswordReset');
    }

    protected function attachUserPasswordEmailWidgets($userPasswordEmail)
    {
        $userPasswordEmail->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userPasswordEmail, 'content', 'Theme', 'userPasswordEmail');
    }

    protected function attachAdShowWidgets($adShow)
    {
        $adShow->themepartials()->attach(Themepartial::all());
        $this->attachWidget($adShow, 'categories', 'Theme', 'AdCategoryAncestors');
        $this->attachWidget($adShow, 'content', 'Theme', 'AdDetails');
        $this->attachWidget($adShow, 'sideleft', 'Theme', 'UserContact');
    }

    protected function attachAdSearchWidgets($adSearch)
    {
        $adSearch->themepartials()->attach(Themepartial::all());
        $this->attachWidget($adSearch, 'query', 'Theme', 'SearchQuery');
        $this->attachWidget($adSearch, 'filters', 'Theme', 'SearchFilters');
        $this->attachWidget($adSearch, 'content', 'Theme', 'SearchAdsList');
    }

    protected function attachUserAdListWidgets($userAdList)
    {
        $userAdList->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userAdList, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userAdList, 'content', 'Theme', 'UserAds');
    }

    protected function attachUserAdCreateWidgets($userAdCreate)
    {
        $userAdCreate->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userAdCreate, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userAdCreate, 'content', 'Theme', 'CreateAdForm');
    }

    protected function attachUserAdEditWidgets($userAdEdit)
    {
        $userAdEdit->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userAdEdit, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userAdEdit, 'content', 'Theme', 'EditAdForm');
    }

    protected function attachUserAdtypeListWidgets($userAdtypeList)
    {
        $userAdtypeList->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userAdtypeList, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userAdtypeList, 'content', 'Theme', 'AdtypeList');
    }

    protected function attachUserAdtypeCartWidgets($userAdtypeCart)
    {
        $userAdtypeCart->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userAdtypeCart, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userAdtypeCart, 'content', 'Theme', 'AdtypeCart');
    }

    protected function attachUserSubscriptionListWidgets($userSubscriptionList)
    {
        $userSubscriptionList->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userSubscriptionList, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userSubscriptionList, 'content', 'Theme', 'UserSubscriptionsList');
    }

    protected function attachUserSubscriptionCartWidgets($userSubscriptionCart)
    {
        $userSubscriptionCart->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userSubscriptionCart, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userSubscriptionCart, 'content', 'Theme', 'UserSubscriptionCart');
    }

    protected function attachUserEditWidgets($userEdit)
    {
        $userEdit->themepartials()->attach(Themepartial::all());
        $this->attachWidget($userEdit, 'menu', 'Theme', 'UserMenu');
        $this->attachWidget($userEdit, 'content', 'Theme', 'UserEditForm');
    }

    protected function attachPaymentAcceptedWidgets($paymentAccepted)
    {
        $paymentAccepted->themepartials()->attach(Themepartial::all());

        $config = [
            'type'    => 'code',
            'content' => '<div class="panel panel-default">'.PHP_EOL
            .'   <div class="panel-body">'.PHP_EOL
            .'     <h1>'.trans('frontend.payment.success.title').'</h1>'.PHP_EOL
            .'         <br /><br />'.PHP_EOL
            .'     <p>'.trans('frontend.payment.success.content').'</p>'.PHP_EOL
            .'   </div>'.PHP_EOL
            .'</div>',
        ];
        $this->attachWidget($paymentAccepted, 'content', 'ZEDx', 'Editor', $config);
    }

    protected function attachPaymentCancelledWidgets($paymentCancelled)
    {
        $paymentCancelled->themepartials()->attach(Themepartial::all());
        $config = [
            'type'    => 'code',
            'content' => '<div class="panel panel-default">'.PHP_EOL
            .'   <div class="panel-body">'.PHP_EOL
            .'     <h1>'.trans('frontend.payment.error.title').'</h1>'.PHP_EOL
            .'         <br /><br />'.PHP_EOL
            .'     <p>'.trans('frontend.payment.error.content').'</p>'.PHP_EOL
            .'   </div>'.PHP_EOL
            .'</div>',
        ];
        $this->attachWidget($paymentCancelled, 'content', 'ZEDx', 'Editor', $config);
    }

    protected function attachWidget($page, $blockIdentifier, $namespace, $name, $config = [])
    {
        $contentBlock = $page->template->blocks()->whereIdentifier($blockIdentifier)->first();

        $node = new Widgetnode();
        $node->page_id = $page->id;
        $node->namespace = 'Frontend\\'.$namespace.'\\'.$name;
        $node->title = $name;
        $node->config = json_encode($config);
        $node->is_enabled = true;

        $contentBlock->nodes()->save($node);
    }
}
