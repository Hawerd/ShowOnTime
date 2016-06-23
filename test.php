<?php
            header('Content-Type: application/xml');
            $retXml = "<rows>";
                $retXml .= "<row id='xa'>";
                $retXml .= "<cell>fsd</cell>";
                $retXml .= "</row>";
            $retXml.="</rows>";    
               
            print_r($retXml);
