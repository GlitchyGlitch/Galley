/**
 * @description
 * Joins 2 paths together and makes sure there aren't any duplicate seperators
 *
 * @param parts      The parts of the url to join. eg: ['http://google.com/', '/my-custom/path/']
 * @param separator  The separator for the path, defaults to '/'
 * @returns {string} The combined path
 */
const joinPaths = (parts, separator) => {
  return parts
    .map((part) => part.trim().replace(/(^[\/]*|[\/]*$)/g, ""))
    .join(separator || "/");
};

export { joinPaths };
