package com.example.demo;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

@RestController
public class AddressController {

    private final List<Address> addresses = new ArrayList<>(
            Arrays.asList(
                    new Address("38400100", "Floriano Peixoto", "Centro", "Uberlândia"),
                    new Address("38400200", "Tiradentes", "Fundinho", "Uberlândia"),
                    new Address("38400300", "Lions Clube", "Osvaldo Rezende", "Uberlândia")
            )
    );

    @GetMapping("/address")
    public List<Address> getAddresses() {
        return this.addresses;
    }

    @GetMapping("/address/{cep}")
    public ResponseEntity<Address> getAddress(@PathVariable String cep) {
        for (Address address : this.addresses) {
            if (address.getCep().equals(cep)) {
                return ResponseEntity.ok(address);
            }
        }
        return ResponseEntity.notFound().build();
    }

    @PostMapping("/address")
    public void addAddress(@RequestBody Address address) {
        this.addresses.add(address);
    }

    @DeleteMapping("/address/{cep}")
    public ResponseEntity<Void> deleteAddress(@PathVariable String cep) {
        // removeIf retorna true se algum elemento foi removido
        boolean removed = this.addresses.removeIf(address -> address.getCep().equals(cep));

        if (removed) {
            // Retorna 200 OK (ou 204 No Content) para indicar sucesso
            return ResponseEntity.ok().build();
        } else {
            // Retorna 404 Not Found se o CEP não existia na lista
            return ResponseEntity.notFound().build();
        }
    }

    @GetMapping("/hello")
    public String helloWorld() {
        return "Hello World!";
    }
}