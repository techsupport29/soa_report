import { imageDownload } from "./downloads";
import { readSoa } from "./readSoa";
import { beforeDownload } from "./beforeDownload";
import {sendEmailwithImage} from "./sendEmailwithImage";
import { truncate } from "./truncate";
import { withStatus, soa } from "./soaList";
import { reportGenerate } from "./generateReport";
import { computationSoa } from "./computationFormat";
import { printSoa } from "./print";

export {
    imageDownload,
    sendEmailwithImage,
    readSoa,
    truncate,
    beforeDownload,
    soa,
    withStatus,
    reportGenerate,
    computationSoa,
    printSoa,

};
