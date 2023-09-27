<table>
  <thead>
    <tr>
      <th colspan="2">
        Bestilling
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Fullt navn</td>
      <td><?= $formdata['Patient']['FirstName'].' '.$formdata['Patient']['LastName'] ?></td>
    </tr>
    <tr>
      <td>E-post</td>
      <td><?= $formdata['Patient']['Email'] ?></td>
    </tr>
    <tr>
      <td>Mobilnummer</td>
      <td><?= $formdata['Patient']['MobilePhoneNumber'] ?></td>
    </tr>
    <tr>
      <td>Fødselsnummer</td>
      <td><?= $formdata['Patient']['PatientPersonalIdentification'] ?></td>
    </tr>
    <tr>
      <td>Adresse</td>
      <td>
        <?= $formdata['Patient']['Adress1'] ?><br>
        <?= $formdata['Patient']['PostalCode'] ?> <?= $formdata['Patient']['City'] ?>
      </td>
    </tr>
    <tr>
      <td>Kampanje/vervekode</td>
      <td><?= $formdata['CampaignCode'] ?></td>
    </tr>
    <tr>
      <td>Fritekstfelt</td>
      <td><?= $formdata['FreeTextMessage'] ?></td>
    </tr>
    <tr>
      <td>Behandling</td>
      <td><?= $formdata['treatment'] ?></td>
    </tr>
    <tr>
      <td>Behandler</td>
      <td><?= $formdata['clinician'] ?></td>
    </tr>
    <tr>
      <td>Ønsket dag</td>
      <td>
        <?php $days = empty($_POST['days']) ? 'Hvilken som helst' : implode(', ', $_POST['days']); echo $days; ?>
      </td>
    </tr>
    <tr>
      <td>Ønsket tidspunkt</td>
      <td>
        <?php $times = empty($_POST['times']) ? 'Når som helst' : implode(', ', $_POST['times']); echo $times; ?>
      </td>
    </tr>
  </tbody>
</table>
