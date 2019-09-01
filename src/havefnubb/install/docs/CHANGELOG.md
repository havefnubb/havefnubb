Change log
==========

For version lower than 2.0, see changelog into their corresponding packages.

Version 2.0
------------

Not released yet.

- Upgrade the underlying framework from Jelix 1.3 to Jelix 1.6 
    - some configuration files have been renamed
- Upgrade the authentication system (jCommunity from 0.2 to 1.3)
    - better workflow for registration
    - **New features to reset password** 
        - new page for a user to set a new password after the administration has
          resetted his password.
        - new page for the administrator to reset a password of a user
    - **new page to resend validation email** (by the administrator)
    - **New process to request a password**. There is not anymore a form in which the
      user has to indicate a key and a login. The email contain a link having the
      login and the key.
    - **New process for registration**.
        It follows "modern" processes for the registration:
        - the form contain the login, email but also the password
        - the email indicate a link, which contain the registration key
          so the user do not need anymore to fill a new form
    - **User profile: improve the privacy**.
      A configuration property, publicProperties, allows to
      specify which fields are public, so only these fields
      are shown to any visitor.
    - **sends emails in HTML** instead of in plain text.
    - **New form allowing user to change its password** when he is authenticated
    - Account deletion: ask the password account to confirm
    - improved installation of the authentication. More configuration parameters


Removed features or data:

- remove all themes. Too old and too hard to maintain. The theme system is still
  there though. 
- locales en_EN are replaced by en_US







