<?php

namespace App\Enums;

enum PermissionsEnum: string
{

    // Permission Permissions
    case VIEW_PERMISSIONS = 'view_permissions';

    // Role Permissions
    case VIEW_ROLES = 'view_roles';
    case VIEW_ROLE = 'view_role';
    case DELETE_ROLE = 'delete_role';
    case UPDATE_ROLE = 'update_role';
    case CREATE_ROLE = 'create_role';

    // User Permissions
    case VIEW_USERS = 'view_users';
    case VIEW_USER = 'view_user';
    case CHANGE_USER_ROLES = 'change_user_roles';
    case CHANGE_USER_STATUS = 'change_user_status';
    case DELETE_USER = 'delete_user';
    case UPDATE_USER = 'update_user';
    case CREATE_USER = 'create_user';

    // Category Permissions
    case VIEW_CATEGORIES = 'view_categories';
    case VIEW_CATEGORY = 'view_category';
    case DELETE_CATEGORY = 'delete_category';
    case UPDATE_CATEGORY = 'update_category';
    case CREATE_CATEGORY = 'create_category';

    // Product Permissions
    case VIEW_PRODUCTS = 'view_products';
    case VIEW_PRODUCT = 'view_product';
    case DELETE_PRODUCT = 'delete_product';
    case UPDATE_PRODUCT = 'update_product';
    case CREATE_PRODUCT = 'create_product';

    // Order Permissions
    case VIEW_ORDERS = 'view_orders';
    case VIEW_ORDER = 'view_order';
    case UPDATE_ORDER = 'update_order';
    case PRINT_ORDER = 'print_order';
    case CHANGE_ORDER_STATUS = 'change_order_status';

    // Admin Permissions
    case VIEW_DASHBOARD = 'view_dashboard';

    // Message Permissions
    case VIEW_MESSAGES = 'view_messages';
    case MARK_MESSAGE_AS_READ = 'mark_message_as_read';
    case DELETE_MESSAGE = 'delete_message';
    case MARK_ALL_MESSAGES_AS_READ = 'mark_all_messages_as_read';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
