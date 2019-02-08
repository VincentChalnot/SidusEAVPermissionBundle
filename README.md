Sidus/EAVPermissionBundle Documentation
==================================

This bundle allows you to define role-based permissions for the Sidus/EAVModelBundle.

It supports permissions on families, attributes and EAVData through their families. (So no entity-level permissions)

## Quick example

Roles are just meant as an example, there is no hard-coded role in this bundle.

````yaml
sidus_eav_model:
    families:
        Post:
            attributeAsLabel: title
            options:
                permissions:
                    # list: [] # Don't define a permission: means granted for all
                    edit: [ROLE_DATA_ADMIN]
                    delete: [] # Defined but left empty: deny access for all
            attributes:
                title:
                    required: true

                content:
                    type: html

                publicationDate:
                    type: datetime
                    options:
                        permissions:
                            read: [ROLE_DATA_MANAGER]
                            edit: [ROLE_DATA_ADMIN]
````

#### Family permissions
There are no rules inside the EAVModelBundle that checks these permissions so it's up to you to check these through
Symfony's security component:
````php
<?php

use Sidus\EAVModelBundle\Model\FamilyInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/** @var FamilyInterface $family */
/** @var AuthorizationCheckerInterface $securityChecker **/
$securityChecker->isGranted('edit', $family);
````

|  Note   |
|:-------:|
| If you are using the [EAV Manager](https://github.com/cleverage/eav-manager) these permissions are already checked for in datagrids and actions |

#### Attribute permissions
Attribute permissions are supported natively in the EAVModelBundle through the form component.

If no permission option is set, it means the attribute will be editable for everyone.
If the attribute is readable but not editable, the form type will appear disabled
