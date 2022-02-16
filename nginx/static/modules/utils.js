import Component from "/modules/component.js";

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

const isComponentArray = (value) => {
  if (Array.isArray(value))
    if (!value.length <= 0)
      if (
        value
          .map((item) => item instanceof Component)
          .reduce((accumulator, current) => accumulator && current)
      )
        return true;

  return false;
};

const convertDate = (dateStr) => {
  const [Y, M, D, h, m, s] = dateStr.split(/[- :]/);
  const date = new Date(Y, parseInt(M) - 1, D, h, m, s);
  const year = date.getFullYear();

  let month = "" + (date.getMonth() + 1);
  let day = "" + date.getDate();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  return [day, month, year].join(".");
};

const fileToBase64 = (file) =>
  new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () =>
      resolve({
        mime: file.type,
        data: reader.result.split(",")[1],
      });
    reader.onerror = (error) => reject(error);
  });

export { joinPaths, isComponentArray, convertDate, fileToBase64 };
