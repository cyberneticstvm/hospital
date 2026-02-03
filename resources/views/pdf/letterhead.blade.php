<!DOCTYPE html>
<html>

<head>
    <title>Devi Eye Clinic & Opticians</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /*@font-face {
            font-family: 'malayalam';
            src: url("{{ storage_path('/fonts/TholikaTraditionalUnicode.ttf') }}") format("truetype");
        }*/
        body {
            background-image: url('./storage/assets/images/letter-head.jpeg');
            width: 100%;
            background-size: cover;
        }

        .text-blue {
            color: blue;
        }

        table {
            border-bottom: 1px solid #000;
        }

        .text-medium {
            font-size: 12px;
        }

        .text-small {
            font-size: 10px;
        }

        .text-large {
            font-size: 15px;
        }

        .from {
            margin-top: 20%;
            margin-left: 6%;
        }

        .to {
            margin-top: 1%;
            margin-left: 6%;
        }

        .subject {
            margin-top: 2%;
            margin-bottom: 2%;
            text-decoration: underline;
        }

        .matter {
            text-align: justify;
            margin-left: 6%;
            margin-right: 2%;
        }

        .text-end {
            margin-left: 75%;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="from">From: <span class="text-end">{{ date('d/M/Y', strtotime($matter->date)) }}</span><br />{!! nl2br($matter->from) !!} </div>
    <div class="to">To: <br />{!! nl2br($matter->to) !!}</div>
    <center class="subject">{{ $matter->subject }}</center>
    <div class="matter">
        Surgery Consumables & Medicines Statement<br>
        Patient Name: Mrs. K. Sarasamma (65/F)<br>
        MR ID: 705<br>
        Bill No & Date: 4/18 dated 18 Nov 2025<br>
        <table width="100%">
            <thead>
                <tr>
                    <th>SL No</th>
                    <th>Consumable Name</th>
                    <th>Qty</th>
                    <th>Unit Price (Rs.)</th>
                    <th>Total (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>SURGEN'S CHARGE -N</td>
                    <td> 1</td>
                    <td> 4000.00</td>
                    <td> 4000.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>NURSING CHARGE</td>
                    <td> 4</td>
                    <td> 312.00</td>
                    <td> 1248.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>OT CHARGES</td>
                    <td> 1</td>
                    <td> 5000.00</td>
                    <td> 5000.00</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>IOL (Foldable)-Alcon HD UV -USA</td>
                    <td> 1</td>
                    <td> 18500.00</td>
                    <td> 18500.00</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>UltraSert-Alcon</td>
                    <td> 1</td>
                    <td> 2485.00</td>
                    <td> 2485.00</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Disposable injector Alcon</td>
                    <td> 1</td>
                    <td> 2500.00</td>
                    <td> 2500.00</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>IRIGAN-Alcon 500ML</td>
                    <td> 1</td>
                    <td> 1912.00</td>
                    <td> 1912.00</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>GLOVE 7</td>
                    <td> 3</td>
                    <td> 80.00</td>
                    <td> 240.00</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>GLOVE 6.5</td>
                    <td> 3</td>
                    <td> 80.00</td>
                    <td> 240.00</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>VISC-3ML</td>
                    <td> 2</td>
                    <td> 450.00</td>
                    <td> 900.00</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>ing-LIGNOCAIN</td>
                    <td> 1</td>
                    <td> 32.00</td>
                    <td> 32.00</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>ing-ZYYONATE</td>
                    <td> 1</td>
                    <td> 513.00</td>
                    <td> 513.00</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>ing-ANAVIN</td>
                    <td> 1</td>
                    <td> 86.00</td>
                    <td> 86.00</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>NS</td>
                    <td> 1</td>
                    <td> 34.00</td>
                    <td> 34.00</td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>IV SET</td>
                    <td> 1</td>
                    <td> 110.00</td>
                    <td> 110.00</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>SURGICAL BLADE</td>
                    <td> 2</td>
                    <td> 16.00</td>
                    <td> 32.00</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>KERATOME</td>
                    <td> 2</td>
                    <td> 100.00</td>
                    <td> 200.00</td>
                </tr>
                <tr>
                    <td>18</td>
                    <td>CRESENT</td>
                    <td> 1</td>
                    <td> 120.00</td>
                    <td> 120.00</td>
                </tr>
                <tr>
                    <td>19</td>
                    <td>SYRINGE-2.50ML</td>
                    <td> 4</td>
                    <td> 7.00</td>
                    <td> 28.00</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>SYRINGE-5ML</td>
                    <td> 2</td>
                    <td> 9.00</td>
                    <td> 18.00</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>MASK -SURGICAL</td>
                    <td> 1</td>
                    <td> 7.00</td>
                    <td> 7.00</td>
                </tr>
                <tr>
                    <td>22</td>
                    <td>DISPOSABLE CAP-W</td>
                    <td> 1</td>
                    <td> 7.00</td>
                    <td> 7.00</td>
                </tr>
                <tr>
                    <td>23</td>
                    <td>DISPOSABLE GOWN</td>
                    <td> 1</td>
                    <td> 270.00</td>
                    <td> 270.00</td>
                </tr>
                <tr>
                    <td>24</td>
                    <td>ANGULAR CANNULA-24G (DISPOSABLE)</td>
                    <td> 1</td>
                    <td> 25.00</td>
                    <td> 25.00</td>
                </tr>
                <tr>
                    <td>25</td>
                    <td>BETADINE SOLUTION 0.5%</td>
                    <td> 1</td>
                    <td> 60.00</td>
                    <td> 60.00</td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <br>
        <br>
        <table width="100%">
            <tbody>
                <tr>
                    <td colspan="4">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td>26</td>
                    <td>TROPICAL PLUS DROPS</td>
                    <td> 1</td>
                    <td> 59.00</td>
                    <td> 59.00</td>
                </tr>
                <tr>
                    <td>27</td>
                    <td>SURGICAL GOWN</td>
                    <td> 2</td>
                    <td> 220.00</td>
                    <td> 440.00</td>
                </tr>
                <tr>
                    <td>28</td>
                    <td>EYE DRAPE with DRAIN BAG</td>
                    <td> 1</td>
                    <td> 110.00</td>
                    <td> 110.00</td>
                </tr>
                <tr>
                    <td>29</td>
                    <td>SURGICAL instrument Drapes</td>
                    <td> 3</td>
                    <td> 80.00</td>
                    <td> 240.00</td>
                </tr>
                <tr>
                    <td>30</td>
                    <td>4 QUIN- PFS 0.5 ml</td>
                    <td> 1</td>
                    <td> 280.00</td>
                    <td> 280.00</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="fw-bold text-end">39694.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Discount / Insurance / Others</td>
                    <td class="fw-bold">4694</td>
                </tr>
                <tr>
                    <td colspan="4" class="fw-bold">35000.00</td>
                </tr>
            </tfoot>
        </table>
        <br>
        This statement is issued for insurance claim purpose only.<br><br>


        Authorized Signatory<br>
        Devi Eye Hospital<br><br>

        Customer Care: 93886 11622<br>
        WhatsApp Support: 85473 36622<br>
        Sasthamkotta Branch: 79026 00622
    </div>
</body>

</html>