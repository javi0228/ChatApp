const get_images_url = () => {
    return `${window.app_config.asset_url}/img`;
};

const get_default_chat_room_image = () => {
    return `${get_images_url()}/default-user.webp`;
};

const get_chat_room_image = (image = null) => {
    return image
        ? `${get_images_url()}/chat_rooms/${image}`
        : get_default_chat_room_image();
};

export { get_images_url, get_chat_room_image, get_default_chat_room_image };
